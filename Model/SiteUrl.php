<?php

declare(strict_types=1);

/**
 * URLs absolues (QR quiz). Chemin /…/View via DOCUMENT_ROOT + SCRIPT_FILENAME.
 * Depuis localhost, remplace l’hôte par la meilleure IPv4 LAN détectée (téléphone sur le même Wi‑Fi).
 */
class SiteUrl
{
    /** @var array<string,mixed>|null */
    private static ?array $appConfig = null;

    /** @return array<string,mixed> */
    private static function app(): array
    {
        if (self::$appConfig === null) {
            $path = __DIR__ . '/config.php';
            $cfg = is_file($path) ? require $path : [];
            self::$appConfig = is_array($cfg) ? $cfg : [];
        }

        return self::$appConfig;
    }

    public static function httpOrigin(): string
    {
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443');
        $scheme = $https ? 'https' : 'http';
        $host = (string) ($_SERVER['HTTP_HOST'] ?? '');
        if ($host === '') {
            $host = (string) ($_SERVER['SERVER_NAME'] ?? 'localhost');
        }
        if (!str_contains($host, ':')) {
            $port = (int) ($_SERVER['SERVER_PORT'] ?? 0);
            if ($port > 0
                && (($scheme === 'http' && $port !== 80) || ($scheme === 'https' && $port !== 443))) {
                $host .= ':' . $port;
            }
        }

        return $scheme . '://' . $host;
    }

    /** Origine utilisée dans les liens / QR quiz (remplace localhost par IP LAN si activé). */
    public static function httpOriginForQr(): string
    {
        $o = self::httpOrigin();
        if (!(bool) (self::app()['app']['qr_use_lan_ip_when_localhost'] ?? true)) {
            return $o;
        }
        $parts = parse_url($o);
        if ($parts === false || empty($parts['host'])) {
            return $o;
        }
        $host = strtolower((string) $parts['host']);
        $loopback = $host === 'localhost' || $host === '127.0.0.1' || $host === '::1';
        if (!$loopback) {
            return $o;
        }
        $candidates = self::collectLanIpv4Candidates();
        $lan = self::pickBestLanIpv4($candidates);
        if ($lan === null || $lan === '127.0.0.1') {
            return $o;
        }
        $scheme = (string) ($parts['scheme'] ?? 'http');
        $port = isset($parts['port']) ? ':' . (int) $parts['port'] : '';

        return $scheme . '://' . $lan . $port;
    }

    /** Infos diagnostic (page check_network.php). */
    public static function qrNetworkHelp(): array
    {
        $candidates = self::collectLanIpv4Candidates();
        $localFile = __DIR__ . DIRECTORY_SEPARATOR . 'local_public_url.txt';

        return [
            'http_origin_browser' => self::httpOrigin(),
            'http_origin_for_qr'  => self::httpOriginForQr(),
            'view_path'           => self::viewBasePath(),
            'lan_ip_candidates'   => $candidates,
            'lan_ip_chosen'       => self::pickBestLanIpv4($candidates),
            'php_sockets'         => function_exists('socket_create') && function_exists('socket_connect'),
            'shell_exec_ok'       => self::isShellExecAvailable(),
            'config_public_url_set' => trim((string) (self::app()['app']['public_base_url'] ?? '')) !== '',
            'local_url_file_exists' => is_file($localFile),
            'effective_public_base' => self::publicBaseUrlOverride(),
        ];
    }

    /**
     * URL de base (jusqu’au dossier View) forcée pour les QR : config.php puis fichier Model/local_public_url.txt.
     */
    public static function publicBaseUrlOverride(): string
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        $c = trim((string) (self::app()['app']['public_base_url'] ?? ''));
        if ($c !== '') {
            return $cache = rtrim($c, '/');
        }
        $f = __DIR__ . DIRECTORY_SEPARATOR . 'local_public_url.txt';
        if (!is_file($f)) {
            return $cache = '';
        }
        $raw = (string) @file_get_contents($f);
        if ($raw === '') {
            return $cache = '';
        }
        $first = preg_split('/\R/', $raw, 2)[0] ?? '';
        $first = trim((string) $first);
        $first = preg_replace('/^\xEF\xBB\xBF/', '', $first);

        return $cache = rtrim($first, '/');
    }

    private static function isShellExecAvailable(): bool
    {
        if (!function_exists('shell_exec')) {
            return false;
        }
        $d = ini_get('disable_functions');
        if (!is_string($d) || $d === '') {
            return true;
        }

        return !str_contains($d, 'shell_exec');
    }

    /** @return list<string> */
    private static function collectLanIpv4Candidates(): array
    {
        $out = [];
        foreach ([['8.8.8.8', 53], ['1.1.1.1', 53], ['223.5.5.5', 53], ['8.8.8.8', 443]] as $t) {
            $ip = self::udpLocalIp($t[0], $t[1]);
            if ($ip !== null) {
                $out[] = $ip;
            }
        }
        $hn = @gethostname();
        if (is_string($hn) && $hn !== '') {
            $g = @gethostbyname($hn);
            if (is_string($g) && $g !== $hn && filter_var($g, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $out[] = $g;
            }
        }
        $srv = (string) ($_SERVER['SERVER_ADDR'] ?? '');
        if (filter_var($srv, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && $srv !== '127.0.0.1') {
            $out[] = $srv;
        }
        if (stripos(PHP_OS, 'WIN') === 0 && self::isShellExecAvailable()) {
            foreach (self::ipv4FromIpconfig() as $ip) {
                $out[] = $ip;
            }
        }

        return array_values(array_unique(array_filter($out)));
    }

    /** @return list<string> */
    private static function ipv4FromIpconfig(): array
    {
        $raw = @shell_exec('ipconfig 2>nul');
        if (!is_string($raw) || $raw === '') {
            return [];
        }
        $ips = [];
        if (preg_match_all('/IPv4[^:]*:\s*(\d+\.\d+\.\d+\.\d+)/i', $raw, $m)) {
            foreach ($m[1] as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    $ips[] = $ip;
                }
            }
        }

        return $ips;
    }

    private static function udpLocalIp(string $remoteHost, int $remotePort): ?string
    {
        if (!function_exists('socket_create') || !function_exists('socket_connect')) {
            return null;
        }
        $s = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if ($s === false) {
            return null;
        }
        if (@socket_connect($s, $remoteHost, $remotePort) === false) {
            socket_close($s);

            return null;
        }
        $addr = '';
        $p = 0;
        if (@socket_getsockname($s, $addr, $p) === false) {
            socket_close($s);

            return null;
        }
        socket_close($s);
        if ($addr === '' || !filter_var($addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return null;
        }

        return $addr;
    }

    /**
     * @param list<string> $ips
     */
    private static function pickBestLanIpv4(array $ips): ?string
    {
        $best = null;
        $bestScore = -1;
        foreach (array_unique($ips) as $ip) {
            if (!is_string($ip) || !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                continue;
            }
            if ($ip === '127.0.0.1' || str_starts_with($ip, '169.254.')) {
                continue;
            }
            $score = 0;
            if (str_starts_with($ip, '192.168.')) {
                $score = 100;
            } elseif (str_starts_with($ip, '10.')) {
                $score = 85;
            } elseif (preg_match('#^172\.(1[6-9]|2[0-9]|3[0-1])\.#', $ip) === 1) {
                $score = 70;
            } else {
                $score = 25;
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $ip;
            }
        }

        return $best;
    }

    /**
     * Chemin URL jusqu’au dossier View (sans slash final), ex. /projet web/View.
     * Chaîne vide = la racine Apache pointe déjà sur le dossier View (URL = /frontoffice/...).
     */
    public static function viewBasePath(): string
    {
        $fromFs = self::viewBasePathFromFilesystem();
        if ($fromFs !== null) {
            return $fromFs;
        }

        $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/'));
        $dir = dirname($script);
        $guard = 0;
        while ($dir !== '/' && $dir !== '.' && $dir !== '' && $guard++ < 32) {
            if (strcasecmp(basename($dir), 'View') === 0) {
                return rtrim($dir, '/');
            }
            $next = dirname($dir);
            if ($next === $dir) {
                break;
            }
            $dir = $next;
        }
        $parts = array_values(array_filter(explode('/', trim($script, '/'))));
        if (isset($parts[0]) && strcasecmp((string) $parts[0], 'frontoffice') === 0) {
            return '';
        }

        return rtrim(dirname(dirname($script)), '/');
    }

    /** @return null|string null = utiliser le fallback SCRIPT_NAME */
    private static function viewBasePathFromFilesystem(): ?string
    {
        $dr = isset($_SERVER['DOCUMENT_ROOT']) ? @realpath((string) $_SERVER['DOCUMENT_ROOT']) : false;
        $sf = isset($_SERVER['SCRIPT_FILENAME']) ? @realpath((string) $_SERVER['SCRIPT_FILENAME']) : false;
        if ($dr === false || $sf === false) {
            return null;
        }
        $drN = str_replace('\\', '/', $dr);
        $sfN = str_replace('\\', '/', $sf);
        if (!str_starts_with($sfN, rtrim($drN, '/') . '/') && !str_starts_with($sfN, $drN)) {
            return null;
        }
        $rel = substr($sfN, strlen(rtrim($drN, '/')));
        $rel = ltrim($rel, '/');
        if ($rel === '') {
            return null;
        }
        $dir = dirname($rel);
        if ($dir === '.' || $dir === '') {
            return null;
        }
        $parts = explode('/', $dir);
        foreach ($parts as $i => $seg) {
            if (strcasecmp((string) $seg, 'View') === 0) {
                $prefix = implode('/', array_slice($parts, 0, $i + 1));

                return '/' . $prefix;
            }
        }
        $top = (string) ($parts[0] ?? '');
        if (strcasecmp($top, 'frontoffice') === 0) {
            return '';
        }

        return null;
    }

    /**
     * Origine + chemin pour URLs publiques du type /frontoffice/… telles qu’Apache les affiche.
     * Si la racine Apache est le dossier View du projet, l’URL ne contient pas « /View » : on enlève donc
     * ce segment du chemin technique et on normalise public_base_url / local_public_url.txt (sans /View final).
     */
    public static function absoluteFrontOfficeBase(): string
    {
        $override = self::publicBaseUrlOverride();
        if ($override !== '') {
            return rtrim((string) preg_replace('#/View$#i', '', rtrim($override, '/')), '/');
        }
        $origin = self::httpOriginForQr();
        $base = self::viewBasePath();
        if ($base !== '' && preg_match('#/View$#i', $base)) {
            $base = (string) preg_replace('#/View$#i', '', $base);
        }

        return rtrim($origin . rtrim($base, '/'), '/');
    }

    public static function quizAbsoluteUrl(string $token): string
    {
        $token = trim($token);
        if ($token === '') {
            return '';
        }

        return self::absoluteFrontOfficeBase() . '/frontoffice/blog/quiz.php?t=' . rawurlencode($token);
    }
}
