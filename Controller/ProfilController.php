<?php
require_once __DIR__ . '/../Model/User.php';

class ProfilController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function show(): void {
        $user   = $this->userModel->findById((int)$_SESSION['user_id']);
        $errors = [];
        require_once __DIR__ . '/../View/frontoffice/profil.php';
    }

    public function update(): void {
        $id     = (int)$_SESSION['user_id'];
        $errors = [];
        $user   = $this->userModel->findById($id);

        // Gestion avatar
        $avatar = null;
        if (!empty($_POST['remove_avatar'])) {
            if (!empty($user['avatar'])) {
                $oldFile = __DIR__ . '/../uploads/avatars/' . $user['avatar'];
                if (file_exists($oldFile)) unlink($oldFile);
            }
            $avatar = '';
        } elseif (!empty($_FILES['avatar']['name'])) {
            $ext     = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($ext, $allowed)) {
                $errors['avatar'] = "Format non autorisé (jpg, png, gif, webp).";
            } elseif ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
                $errors['avatar'] = "Image trop grande (max 2 Mo).";
            } else {
                $filename = 'avatar_' . $id . '_' . time() . '.' . $ext;
                $dest     = __DIR__ . '/../uploads/avatars/' . $filename;
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
                    // Supprimer l'ancienne photo
                    if (!empty($user['avatar'])) {
                        $oldFile = __DIR__ . '/../uploads/avatars/' . $user['avatar'];
                        if (file_exists($oldFile)) unlink($oldFile);
                    }
                    $avatar = $filename;
                }
            }
        }

        if (empty($errors)) {
            if ($avatar !== null) {
                $this->userModel->update($id, $user['nom'], $user['prenom'], $user['email'], $user['role'], $avatar);
            }
            $_SESSION['success'] = "Photo de profil mise à jour !";
            header('Location: index.php?page=profil');
            exit;
        }

        require_once __DIR__ . '/../View/frontoffice/profil.php';
    }
}
