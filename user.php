<?php
require_once("init.php");

// kick out if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: auth.php");
    exit;
}

// db config. will be moved later
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "users";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
    <title>My Profile</title>
    <link rel="stylesheet" href="./style/main.css">
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .dashboard-container { max-width: 800px; margin: 2em auto; background: grey; padding: 30px; border-radius: 0.75em; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .section { margin-top: 30px; border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 4px; }
        .success { background: #dff0d8; color: #3c763d; border: 1px solid #d6e9c6; }
        .danger { background: #f2dede; color: #a94442; border: 1px solid #ebccd1; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #337ab7; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-delete { background: #d9534f; margin-top: 10px; }
    </style>
</head>
<body>
<?php include './template/navbar.php'; ?>
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

    <div class="section" style="border-color: #ebccd1;">
        <h2 style="color: #a94442;">Danger Zone</h2>
        <p>Once you delete your account, there is no going back. Please be certain.</p>
        <form action="user.php" method="POST" onsubmit="return confirm('Do you really want to delete your account?');">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" name="delete_account" class="btn-delete">Delete My Account</button>
        </form>
    </div>

    <p style="margin-top: 20px;"><a href="./admin_logout.php">Logout</a></p>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>
    <script src="functions.js"></script>

    <div id="timeoutModal" title="SYSTEM_WARNING" style="display: none;">
        <p>Bist du noch da? Deine Sitzung läuft in wenigen Minuten ab.</p>
    </div>
    <div id="timeoutModal" title="SYSTEM_WARNING" style="display: none;">
  <p>Bist du noch da? Deine Sitzung läuft in wenigen Minuten ab.</p>
</div>

<style>
    .ui-dialog { background: #0d1117 !important; border: 2px solid #4ade80 !important; border-radius: 8px !important; box-shadow: 0 0 15px rgba(74, 222, 128, 0.2) !important; }
    .ui-dialog-titlebar { background: transparent !important; border: none !important; border-bottom: 1px solid rgba(74, 222, 128, 0.3) !important; color: #4ade80 !important; font-family: 'Audiowide', sans-serif !important; }
    .ui-dialog-content { background: transparent !important; color: white !important; font-family: 'Quantico', sans-serif !important; text-align: center !important; padding: 20px !important; }
    .ui-dialog-buttonpane { background: transparent !important; border-top: 1px solid rgba(74, 222, 128, 0.3) !important; margin-top: 0 !important; padding: 10px !important; }
    .ui-dialog .ui-button { background: #4ade80 !important; color: #0d1117 !important; border: none !important; font-family: 'Quantico', sans-serif !important; font-weight: bold !important; padding: 8px 16px !important; margin: 0 10px !important; }
    .ui-dialog .ui-dialog-buttonset button:nth-child(2) { background: transparent !important; color: #ff4757 !important; border: 1px solid #ff4757 !important; }
    .ui-dialog-titlebar-close { display: none !important; }
</style>
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