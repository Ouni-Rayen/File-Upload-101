<?php
session_start();

$upload_dir = __DIR__ . '/uploads/';
$message = '';
$avatar = isset($_SESSION['avatar']) ? $_SESSION['avatar'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $file = $_FILES['avatar'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        // ONLY check Content-Type — the vulnerability
        $type = $file['type'] ?? '';

        if (str_starts_with($type, 'image/')) {
            // Keep original filename (dangerous)
            $name = basename($file['name']);
            // Basic safety: no path traversal in name
            $name = str_replace(['..', '/', '\\'], '', $name);

            if ($name !== '') {
                $dest = $upload_dir . $name;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $_SESSION['avatar'] = 'uploads/' . $name;
                    $avatar = $_SESSION['avatar'];
                    $message = 'Profile picture updated successfully.';
                } else {
                    $message = 'Failed to save file.';
                }
            } else {
                $message = 'Invalid filename.';
            }
        } else {
            $message = 'Only image files are allowed (invalid Content-Type).';
        }
    } else {
        $message = 'Upload error. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="logo.jpg" type="image/jpeg">
</head>
<body>
    <header>
        <div class="header-inner">
            <a href="/" class="brand">
                <img src="logo.jpg" alt="" class="logo">
                <span>Account</span>
            </a>
        </div>
    </header>

    <main>
        <section class="card">
            <h1>Profile Settings</h1>
            <p class="subtitle">Manage your account information</p>

            <div class="profile-preview">
                <?php if ($avatar && file_exists(__DIR__ . '/' . $avatar)): ?>
                    <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="avatar-img">
                <?php else: ?>
                    <div class="avatar-placeholder">?</div>
                <?php endif; ?>
                <div>
                    <p class="username">Member</p>
                    <p class="muted">Update your profile picture below</p>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="alert"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <label for="avatar">Profile picture</label>
                <input type="file" id="avatar" name="avatar" accept="image/*">
                <p class="hint">Accepted formats: JPG, PNG, GIF</p>
                <button type="submit" class="btn">Save changes</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025</p>
    </footer>
</body>
</html>
