<?php
require_once("init.php");
require_once("functions.php");
require_once("config.php");

// kick out if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: auth.php");
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
$current_email = $current_user_data['email'] ?? 'None provided yet';

// handle form submits
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // security check: validate csrf token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

    // 1. update email
    if (isset($_POST['update_email'])) {
        $new_email = filter_var($_POST['new_email'], FILTER_SANITIZE_EMAIL);
        if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $sql = "UPDATE user SET email = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_email, $user_id);
            if ($stmt->execute()) {
                $message = "Email updated successfully!";
                $current_email = $new_email;
            } else {
                $error = "Error updating email.";
            }
        } else {
            $error = "Invalid email format.";
        }
    }

    // 2. update password
    if (isset($_POST['update_password'])) {
        $current_pw = $_POST['current_password'];
        $new_pw = $_POST['new_password'];
        $confirm_pw = $_POST['confirm_password'];

        if ($new_pw !== $confirm_pw) {
            $error = "New passwords do not match.";
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
                $message = "Password updated successfully!";
            } else {
                $error = "Current password is incorrect.";
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
            header("Location: auth.php?msg=account_deleted");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="./style/main.css">
</head>
<body class="page-user">
<?php 
if (is_mobile()) {
    include './template/navbar_mobile.php'; 
} else {
    include './template/navbar.php';        
} 
?>
<div class="dashboard-container">
    <h1>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

    <?php if($message): ?> <div class="alert success"><?php echo $message; ?></div> <?php endif; ?>
    <?php if($error): ?> <div class="alert danger"><?php echo $error; ?></div> <?php endif; ?>

    <div class="section">
        <h2>Manage Email Address</h2>
        <p><small>Current Email: <strong><?php echo htmlspecialchars($current_email); ?></strong></small></p>
        <form action="user.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <label>New Email:</label>
                <input type="email" name="new_email" required>
            </div>
            <button type="submit" name="update_email">Save Email</button>
        </form>
    </div>

    <div class="section">
        <h2>Change Password</h2>
        <form action="user.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group"><label>Current Password:</label><input type="password" name="current_password" required></div>
            <div class="form-group"><label>New Password:</label><input type="password" name="new_password" required></div>
            <div class="form-group"><label>Confirm New Password:</label><input type="password" name="confirm_password" required></div>
            <button type="submit" name="update_password">Update Password</button>
        </form>
    </div>

    <div class="section section-danger">
        <h2 class="heading-danger">Danger Zone</h2>
        <p>Once you delete your account, there is no going back. Please be certain.</p>
        <form action="user.php" method="POST" onsubmit="return confirm('Do you really want to delete your account?');">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" name="delete_account" class="btn-delete">Delete My Account</button>
        </form>
    </div>

    <form action="./admin_logout.php" class="mt-20">
        <button type="submit" class="btn-delete">Logout</button>
    </form>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>
    <script src="functions.js"></script>

    <div id="timeoutModal" title="SYSTEM_WARNING" class="modal-hidden">
        <p>Bist du noch da? Deine Sitzung läuft in wenigen Minuten ab.</p>
    </div>
    <div id="timeoutModal" title="SYSTEM_WARNING" class="modal-hidden">
  <p>Bist du noch da? Deine Sitzung läuft in wenigen Minuten ab.</p>
</div>

<script>
    var warningTimer, logoutTimer;

    function startMyTimers() {
        clearTimeout(warningTimer);
        clearTimeout(logoutTimer);

        // Timer 1: Nach 10 Minuten Warnung anzeigen
        warningTimer = setTimeout(function() {
            
            $("#timeoutModal").dialog({
                modal: true,
                width: 400,
                draggable: false, 
                resizable: false, 
                buttons: {
                    "Bleiben": function() {
                        fetch('keep_alive.php'); 
                        $(this).dialog("destroy"); // Reißt das Fenster restlos ab
                        startMyTimers(); // Startet die 3 Sekunden wieder von vorn
                    },
                    "Ausloggen": function() {
                        // FIX: Leitet jetzt auf dein echtes Logout-Skript um!
                        window.location.href = 'admin_logout.php';
                    }
                }
            });

            // Timer 2: Nach weiteren 2 Minuten automatischer Logout
            logoutTimer = setTimeout(function() {
                window.location.href = 'admin_logout.php';
            }, 120000);

        }, 600000);
    }

    // Wenn die Seite geladen ist und jQuery bereit ist: Start!
    $(document).ready(function() {
        if ($("#timeoutModal").length > 0) {
            startMyTimers();
        }
    });
</script>
</body>
</html>