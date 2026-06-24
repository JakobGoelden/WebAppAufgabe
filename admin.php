<?php
// pull in session config and security headers.
require_once("init.php");
require_once("functions.php");

// db inputs hardcoded. will be moved at the end
$servername = "localhost";
$username_db   = "root";
$password_db   = "";
$dbname     = "users";

// build db connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// login double check: kick out if session cookie is missing or invalid.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: auth.php");
    exit;
}

// privilege check: verify if the user has admin rights (is_admin = 1).
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: index.php");
    exit;
}

$msg = '';
$error = '';

// POST Requests verarbeiten (CRUD Aktionen)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check für ALLE Aktionen!
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF Token ungültig!");
    }

    $action = $_POST['action'] ?? '';

    // 1. NEUEN USER ANLEGEN
    if ($action === 'create_user') {
        $new_user = trim($_POST['new_username']);
        $new_email = trim($_POST['new_email']);
        $new_pass = $_POST['new_password'];
        $new_role = (int)$_POST['new_role']; 

        if(empty($new_user) || empty($new_pass)) {
            $error = "Benutzername und Passwort sind Pflichtfelder!";
        } else {
            $check = $conn->prepare("SELECT id FROM user WHERE username = ?");
            $check->bind_param("s", $new_user);
            $check->execute();
            if ($check->get_result()->num_rows > 0) {
                $error = "Dieser Benutzername existiert bereits.";
            } else {
                $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO user (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sssi", $new_user, $new_email, $hashed_pass, $new_role);
                if($stmt->execute()) {
                    $msg = "User '$new_user' wurde erfolgreich erstellt.";
                } else {
                    $error = "Fehler beim Erstellen des Users.";
                }
            }
        }
    }

    // Für die folgenden Aktionen brauchen wir die Target-ID
    if (isset($_POST['user_id'])) {
        $target_id = (int)$_POST['user_id'];

        // 2. USER LÖSCHEN
        if ($action === 'delete' && $target_id !== $_SESSION['user_id']) {
            $stmt = $conn->prepare("DELETE FROM user WHERE id = ?"); 
            $stmt->bind_param("i", $target_id);
            $stmt->execute();
            $msg = "User gelöscht.";
        }

        // 3. ROLLE ÄNDERN
        if ($action === 'toggle_admin' && $target_id !== $_SESSION['user_id']) {
            $new_status = (int)$_POST['current_role'] === 1 ? 0 : 1;
            $stmt = $conn->prepare("UPDATE user SET is_admin = ? WHERE id = ?");
            $stmt->bind_param("ii", $new_status, $target_id);
            $stmt->execute();
            $msg = "Rolle wurde aktualisiert.";
        }

        // 4. EMAIL UPDATEN
        if ($action === 'update_email') {
            $updated_email = trim($_POST['updated_email']);
            $stmt = $conn->prepare("UPDATE user SET email = ? WHERE id = ?");
            $stmt->bind_param("si", $updated_email, $target_id);
            if($stmt->execute()) {
                $msg = "E-Mail für User #$target_id erfolgreich geändert.";
            }
        }

        // 5. PASSWORT UPDATEN (NEU)
        if ($action === 'update_password') {
            $new_pass = $_POST['new_password'];
            if(!empty($new_pass)) {
                $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE user SET password = ? WHERE id = ?");
                $stmt->bind_param("si", $hashed_pass, $target_id);
                if($stmt->execute()) {
                    $msg = "Passwort für User #$target_id erfolgreich neu gesetzt.";
                } else {
                    $error = "Fehler beim Speichern des Passworts.";
                }
            } else {
                $error = "Das neue Passwort darf nicht leer sein.";
            }
        }
    }
}

// Alle User auslesen für die Tabelle
$result = $conn->query("SELECT id, username, email, is_admin FROM user ORDER BY id DESC");
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./style/main.css">
    <style>
        .admin-wrapper {
            max-width: 1100px; /* Etwas breiter für die extra Spalte */
            margin: 40px auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        .glass-panel {
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 0.125em 0.625em rgba(0,0,0,0.3);
        }

        .glass-panel h2 {
            margin-top: 0;
            font-family: "Audiowide", sans-serif;
            color: #4ade80;
            text-align: left;
            padding: 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }

        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 4px;
            font-family: "Quantico", sans-serif;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #4ade80;
            box-shadow: 0 0 8px rgba(74, 222, 128, 0.4);
        }

        .btn {
            background: rgba(74, 222, 128, 0.2);
            border: 1px solid #4ade80;
            color: #4ade80;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
            font-family: "Quantico", sans-serif;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #4ade80;
            color: #0d1117;
            box-shadow: 0 0 15px rgba(74, 222, 128, 0.6);
        }

        .btn-danger {
            border-color: #ff4757;
            color: #ff4757;
            background: rgba(255, 71, 87, 0.1);
        }

        .btn-danger:hover {
            background: #ff4757;
            color: white;
            box-shadow: 0 0 15px rgba(255, 71, 87, 0.6);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            color: #4ade80;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9em;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .inline-input {
            width: 70%;
            padding: 5px;
            margin-right: 5px;
        }
        
        .alert-success { color: #4ade80; background: rgba(74, 222, 128, 0.1); padding: 10px; border-radius: 4px; border: 1px solid #4ade80; margin-bottom: 20px;}
        .alert-error { color: #ff4757; background: rgba(255, 71, 87, 0.1); padding: 10px; border-radius: 4px; border: 1px solid #ff4757; margin-bottom: 20px;}
    </style>
</head>
<body>

<?php 
if (is_mobile()) {
    include './template/navbar_mobile.php'; 
} else {
    include './template/navbar.php';        
} 
?>

<div class="glow-box box-1"></div>
<div class="glow-box box-2"></div>
<div class="glow-box box-3"></div>
<div class="glow-box box-4"></div>
<div class="glow-box box-5"></div>

<div class="admin-wrapper content">
    
    <h1>SYSTEM_ADMIN</h1>

    <?php if ($msg): ?>
        <div class="alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="glass-panel">
        <h2>[+] Neuen User registrieren</h2>
        <form method="POST" class="form-grid">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="action" value="create_user">
            
            <div>
                <label>Username</label>
                <input type="text" name="new_username" required placeholder="admin_02">
            </div>
            <div>
                <label>E-Mail</label>
                <input type="email" name="new_email" placeholder="user@domain.com">
            </div>
            <div>
                <label>Passwort</label>
                <input type="password" name="new_password" required placeholder="********">
            </div>
            <div>
                <label>Rolle</label>
                <select name="new_role">
                    <option value="0">User</option>
                    <option value="1">Admin</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn" style="width: 100%;">Erstellen</button>
            </div>
        </form>
    </div>

    <div class="glass-panel">
        <h2>[=] User Datenbank</h2>
        <div style="overflow-x: auto;">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>E-Mail</th>
                    <th>Neues Passwort</th>
                    <th>Rolle</th>
                    <th>Aktionen</th>
                </tr>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td>#<?= htmlspecialchars($u['id']) ?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    
                    <td>
                        <form method="POST" style="display:flex; align-items:center;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="action" value="update_email">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <input type="email" name="updated_email" value="<?= htmlspecialchars($u['email'] ?? '') ?>" class="inline-input">
                            <button type="submit" class="btn" style="padding: 5px 10px; font-size: 0.8em;">Save</button>
                        </form>
                    </td>

                    <td>
                        <form method="POST" style="display:flex; align-items:center;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="action" value="update_password">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <input type="password" name="new_password" placeholder="Neues PW..." class="inline-input" required minlength="4">
                            <button type="submit" class="btn" style="padding: 5px 10px; font-size: 0.8em;">Set</button>
                        </form>
                    </td>

                    <td style="color: <?= $u['is_admin'] == 1 ? '#4ade80' : '#ddd' ?>;">
                        <?= htmlspecialchars($u['is_admin'] == 1 ? 'ADMIN' : 'USER') ?>
                    </td>
                    
                    <td>
                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="action" value="toggle_admin">
                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                <input type="hidden" name="current_role" value="<?= $u['is_admin'] ?>">
                                <button type="submit" class="btn" style="padding: 5px 10px; font-size: 0.8em;">
                                    <?= $u['is_admin'] == 1 ? '-> User' : '-> Admin' ?>
                                </button>
                            </form>

                            <form method="POST" style="display:inline;" onsubmit="return confirm('User wirklich löschen?');">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8em;">Löschen</button>
                            </form>
                        <?php else: ?>
                            <span style="color: gray; font-size: 0.8em;">(Dein Account)</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>
<script src="functions.js"></script>
<div id="timeoutModal" title="SYSTEM_WARNING" style="display: none;">
  <p>Bist du noch da? Deine Sitzung läuft in wenigen Minuten ab.</p>
</div>

<style>
    .ui-widget-overlay { z-index: 9998 !important; }
    .ui-dialog { z-index: 9999 !important; background: #0d1117 !important; border: 2px solid #4ade80 !important; border-radius: 8px !important; box-shadow: 0 0 15px rgba(74, 222, 128, 0.2) !important; }
    .ui-dialog-titlebar { background: transparent !important; border: none !important; border-bottom: 1px solid rgba(74, 222, 128, 0.3) !important; color: #4ade80 !important; font-family: 'Audiowide', sans-serif !important; }
    .ui-dialog-content { background: transparent !important; color: white !important; font-family: 'Quantico', sans-serif !important; text-align: center !important; padding: 20px !important; }
    .ui-dialog-buttonpane { background: transparent !important; border-top: 1px solid rgba(74, 222, 128, 0.3) !important; margin-top: 0 !important; padding: 10px !important; display: flex !important; justify-content: center !important; }
    .ui-dialog-buttonpane .ui-dialog-buttonset { display: flex !important; gap: 10px !important; }
    .ui-dialog .ui-button { background: #4ade80 !important; color: #0d1117 !important; border: none !important; font-family: 'Quantico', sans-serif !important; font-weight: bold !important; padding: 8px 16px !important; margin: 0 10px !important; }
    .ui-dialog .ui-dialog-buttonset button:nth-child(2) { background: transparent !important; color: #ff4757 !important; border: 1px solid #ff4757 !important; }
    .ui-dialog-titlebar-close { display: none !important; }
</style>
</body>
</html>

