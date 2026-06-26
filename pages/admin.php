<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/config.php';

// login double check: kick out if session cookie is missing or invalid.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: " . BASE_URL . "pages/auth.php");
    exit;
}

// privilege check: verify if the user has admin rights (is_admin = 1).
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: " . BASE_URL . "index.php");
    exit;
}

$msg = '';
$error = '';

// POST Requests verarbeiten (CRUD)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF Token ungültig!");
    }

    $action = $_POST['action'] ?? '';

    // neuen user anlegen
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

    // target-id für die nächsten aktionen
    if (isset($_POST['user_id'])) {
        $target_id = (int)$_POST['user_id'];

        // user löschen
        if ($action === 'delete' && $target_id !== $_SESSION['user_id']) {
            $stmt = $conn->prepare("DELETE FROM user WHERE id = ?"); 
            $stmt->bind_param("i", $target_id);
            $stmt->execute();
            $msg = "User gelöscht.";
        }

        // rolle äbndern
        if ($action === 'toggle_admin' && $target_id !== $_SESSION['user_id']) {
            $new_status = (int)$_POST['current_role'] === 1 ? 0 : 1;
            $stmt = $conn->prepare("UPDATE user SET is_admin = ? WHERE id = ?");
            $stmt->bind_param("ii", $new_status, $target_id);
            $stmt->execute();
            $msg = "Rolle wurde aktualisiert.";
        }

        // email updaten
        if ($action === 'update_email') {
            $updated_email = trim($_POST['updated_email']);
            $stmt = $conn->prepare("UPDATE user SET email = ? WHERE id = ?");
            $stmt->bind_param("si", $updated_email, $target_id);
            if($stmt->execute()) {
                $msg = "E-Mail für User #$target_id erfolgreich geändert.";
            }
        }

        // passwort updaten
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

// alle user auslesen
$result = $conn->query("SELECT id, username, email, is_admin FROM user ORDER BY id DESC");
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-Dashboard</title>
    <link rel="stylesheet" href="<?= get_url('assets/css/main.css') ?>">
</head>
<body>

<?php 
if (is_mobile()) {
    include __DIR__ . '/../templates/navbar_mobile.php';
} else {
    include __DIR__ . '/../templates/navbar.php';
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
        <h2>[+] Neuen Benutzer registrieren</h2>
        <form method="POST" class="form-grid">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="action" value="create_user">
            
            <div>
                <label>Benutzername</label>
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
                    <option value="0">Benutzer</option>
                    <option value="1">Admin</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-full">Erstellen</button>
            </div>
        </form>
    </div>

    <div class="glass-panel">
        <h2>[=] Benutzer-Datenbank</h2>
        <div class="table-scroll">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Benutzername</th>
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
                        <form method="POST" class="form-inline">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="action" value="update_email">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <input type="email" name="updated_email" value="<?= htmlspecialchars($u['email'] ?? '') ?>" class="inline-input">
                            <button type="submit" class="btn btn-sm">Speichern</button>
                        </form>
                    </td>

                    <td>
                        <form method="POST" class="form-inline">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="action" value="update_password">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <input type="password" name="new_password" placeholder="Neues PW..." class="inline-input" required minlength="4">
                            <button type="submit" class="btn btn-sm">Setzen</button>
                        </form>
                    </td>

                    <td style="color: <?= $u['is_admin'] == 1 ? '#4ade80' : '#ddd' ?>;">
                        <?= htmlspecialchars($u['is_admin'] == 1 ? 'ADMIN' : 'BENUTZER') ?>
                    </td>
                    
                    <td>
                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            <form method="POST" class="form-inline-action">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="action" value="toggle_admin">
                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                <input type="hidden" name="current_role" value="<?= $u['is_admin'] ?>">
                                <button type="submit" class="btn btn-sm">
                                    <?= $u['is_admin'] == 1 ? '-> Benutzer' : '-> Admin' ?>
                                </button>
                            </form>

                            <form method="POST" class="form-inline-action" onsubmit="return confirm('User wirklich löschen?');">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Löschen</button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted">(Dein Account)</span>
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
<script src="<?= get_url('assets/js/functions.js') ?>"></script>
<div id="timeoutModal" title="SYSTEM_WARNING" class="modal-hidden">
  <p>Bist du noch da? Deine Sitzung läuft in wenigen Minuten ab.</p>
</div>

</body>
</html>

