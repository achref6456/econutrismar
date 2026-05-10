<?php

declare(strict_types=1);

class QuizController
{
    public function show(): void
    {
        $token = trim((string) ($_GET['t'] ?? ''));
        if ($token === '') {
            http_response_code(404);
            $pageTitle = 'Quiz';
            require dirname(__DIR__) . '/View/frontoffice/blog_quiz_error.php';
            return;
        }
        $model = new Blog();
        $payload = $model->findPublishedQuizByToken($token);
        if ($payload === null) {
            http_response_code(404);
            $pageTitle = 'Quiz';
            require dirname(__DIR__) . '/View/frontoffice/blog_quiz_error.php';
            return;
        }
        if (!($payload['ready'] ?? false)) {
            $pageTitle = 'Quiz — EcoNutri';
            require dirname(__DIR__) . '/View/frontoffice/blog_quiz_pending.php';
            return;
        }
        $pageTitle = 'Quiz — EcoNutri';
        $questions = $payload['questions'];
        require dirname(__DIR__) . '/View/frontoffice/blog_quiz_play.php';
    }
}
