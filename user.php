<?php
require_once("init.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: auth.php");
    exit;
}


$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "users";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// CSRF Token generieren falls nicht vorhanden
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = "";
$error = "";

// Aktuelle Daten des Users laden (z.B. E-Mail)
$user_id = $_SESSION['user_id'] ?? 0; // Wir brauchen die ID aus der Session
$sql = "SELECT email FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$current_user_data = $stmt->get_result()->fetch_assoc();
$current_email = $current_user_data['email'] ?? 'None provided yet';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // CSRF Check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

    // 1. EMAIL UPDATE
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

    // 2. PASSWORD UPDATE
    if (isset($_POST['update_password'])) {
        $current_pw = $_POST['current_password'];
        $new_pw = $_POST['new_password'];
        $confirm_pw = $_POST['confirm_password'];

        if ($new_pw !== $confirm_pw) {
            $error = "New passwords do not match.";
        } else {
            // Erst altes Passwort prüfen
            $sql = "SELECT password FROM user WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $hash = $stmt->get_result()->fetch_assoc()['password'];

            if (password_verify($current_pw, $hash)) {
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

    // 3. DELETE ACCOUNT
    if (isset($_POST['delete_account'])) {
        $sql = "DELETE FROM user WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
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

</body>
</html>