<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/config.php';

// kick out if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: " . BASE_URL . "pages/auth.php");
    exit;
}

// fallback: generate csrf token if missing (usually handled by init.php)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = "";
$error = "";

// load current user data
$user_id = $_SESSION['user_id'] ?? 0; // grab id from session
$sql = "SELECT email FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$current_user_data = $stmt->get_result()->fetch_assoc();
$current_email = $current_user_data['email'] ?? 'Noch nicht angegeben';

// handle form submits
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // security check: validate csrf token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF-Token-Validierung fehlgeschlagen.");
    }

    // 1. update email
    if (isset($_POST['update_email'])) {
        $new_email = filter_var($_POST['new_email'], FILTER_SANITIZE_EMAIL);
        if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $sql = "UPDATE user SET email = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_email, $user_id);
            if ($stmt->execute()) {
                $message = "E-Mail erfolgreich aktualisiert!";
                $current_email = $new_email;
            } else {
                $error = "Fehler beim Aktualisieren der E-Mail.";
            }
        } else {
            $error = "Ungültiges E-Mail-Format.";
        }
    }

    // 2. update password
    if (isset($_POST['update_password'])) {
        $current_pw = $_POST['current_password'];
        $new_pw = $_POST['new_password'];
        $confirm_pw = $_POST['confirm_password'];

        if ($new_pw !== $confirm_pw) {
            $error = "Die neuen Passwörter stimmen nicht überein.";
        } else {
            // verify current password first
            $sql = "SELECT password FROM user WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $hash = $stmt->get_result()->fetch_assoc()['password'];

            if (password_verify($current_pw, $hash)) {
                // hash new password and update
                $new_hash = password_hash($new_pw, PASSWORD_DEFAULT);
                $sql = "UPDATE user SET password = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $new_hash, $user_id);
                $stmt->execute();
                $message = "Passwort erfolgreich aktualisiert!";
            } else {
                $error = "Das aktuelle Passwort ist falsch.";
            }
        }
    }

    // 3. delete account
    if (isset($_POST['delete_account'])) {
        $sql = "DELETE FROM user WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            // kill session and redirect
            session_destroy();
            header("Location: " . BASE_URL . "pages/auth.php?msg=account_deleted");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mein Profil</title>
    <link rel="stylesheet" href="<?= get_url('assets/css/main.css') ?>">
</head>
<body class="page-user">
<?php 
if (is_mobile()) {
    include __DIR__ . '/../templates/navbar_mobile.php';
} else {
    include __DIR__ . '/../templates/navbar.php';
}
?>
<div class="dashboard-container">
    <h1>Hallo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

    <?php if($message): ?> <div class="alert success"><?php echo $message; ?></div> <?php endif; ?>
    <?php if($error): ?> <div class="alert danger"><?php echo $error; ?></div> <?php endif; ?>

    <div class="section">
        <h2>E-Mail-Adresse verwalten</h2>
        <p><small>Aktuelle E-Mail: <strong><?php echo htmlspecialchars($current_email); ?></strong></small></p>
        <form action="<?= get_url('pages/user.php') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <label>Neue E-Mail:</label>
                <input type="email" name="new_email" required>
            </div>
            <button type="submit" name="update_email">E-Mail speichern</button>
        </form>
    </div>

    <div class="section">
        <h2>Passwort ändern</h2>
        <form action="<?= get_url('pages/user.php') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group"><label>Aktuelles Passwort:</label><input type="password" name="current_password" required></div>
            <div class="form-group"><label>Neues Passwort:</label><input type="password" name="new_password" required></div>
            <div class="form-group"><label>Neues Passwort bestätigen:</label><input type="password" name="confirm_password" required></div>
            <button type="submit" name="update_password">Passwort aktualisieren</button>
        </form>
    </div>

    <div class="section section-danger">
        <h2 class="heading-danger">Gefahrenzone</h2>
        <p>Wenn du dein Konto löschst, gibt es kein Zurück. Bitte sei dir sicher.</p>
        <form action="<?= get_url('pages/user.php') ?>" method="POST" onsubmit="return confirm('Möchtest du dein Konto wirklich löschen?');">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" name="delete_account" class="btn-delete">Mein Konto löschen</button>
        </form>
    </div>

    <form action="<?= get_url('pages/admin_logout.php') ?>" class="mt-20">
        <button type="submit" class="btn-delete">Abmelden</button>
    </form>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>
    <script src="<?= get_url('assets/js/functions.js') ?>"></script>

    <div id="timeoutModal" title="SYSTEM_WARNING" class="modal-hidden">
        <p>Bist du noch da? Deine Sitzung läuft in wenigen Minuten ab.</p>
    </div>
    <div id="timeoutModal" title="SYSTEM_WARNING" class="modal-hidden">
  <p>Bist du noch da? Deine Sitzung läuft in wenigen Minuten ab.</p>
</div>

<script>
    $(document).ready(function() {
        startSessionTimers();
    });
</script>
</body>
</html>