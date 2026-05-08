<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$uploadDir = 'uploads/';
$cleanDir = 'clean/';

if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
if (!is_dir($cleanDir)) mkdir($cleanDir, 0777, true);

$message = "";
$exifData = [];
$originalViewPath = "";
$cleanViewPath = "";
$jsonDownloadPath = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $baseName = time() . '_' . basename($file['name']); // Zeitstempel gegen Duplikate
    $targetFile = $uploadDir . $baseName;
    $cleanFile = $cleanDir . 'clean_' . $baseName;
    $jsonFile = $cleanDir . 'metadata_' . $baseName . '.json';

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        $originalViewPath = $targetFile;
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($targetFile);

        // 1. Metadaten extrahieren & als JSON speichern
        if ($mime === 'image/jpeg') {
            $exifData = @exif_read_data($targetFile);
            if ($exifData) {
                // EXIF-Daten in Datei schreiben
                file_put_contents($jsonFile, json_encode($exifData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $jsonDownloadPath = $jsonFile;
            }
        }

        // 2. Stripping
        if ($mime === 'image/jpeg') {
            $imgResource = imagecreatefromjpeg($targetFile);
            imagejpeg($imgResource, $cleanFile, 90);
        } elseif ($mime === 'image/png') {
            $imgResource = imagecreatefrompng($targetFile);
            imagepng($imgResource, $cleanFile, 6);
        }

        if (isset($imgResource)) {
            imagedestroy($imgResource);
            $cleanViewPath = $cleanFile;
            $message = "✅ Metadaten erfolgreich entfernt!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Metadata Stripper</title>
    <link rel="stylesheet" href="../style/main.css">
    <link rel="stylesheet" href="../style/metadata_stripping.css"
</head>
<body>

    <h1>📷 Metadaten entfernen</h1>
    
    <form class="form-container" method="post" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <button type="submit">Metadaten entfernen</button>
    </form>

    <?php if ($message): ?>
        <p style="color: #00ff00;"><?php echo $message; ?></p>
    <?php endif; ?>

    <div class="preview-container">
        <?php if ($originalViewPath): ?>
            <div class="preview-box">
                <strong>Original:</strong>
                <img src="<?php echo $originalViewPath; ?>">
            </div>
        <?php endif; ?>

        <?php if ($cleanViewPath): ?>
            <div class="preview-box">
                <strong>Bereinigt:</strong>
                <img src="<?php echo $cleanViewPath; ?>">
                <a href="<?php echo $cleanViewPath; ?>" download class="btn">Bild herunterladen</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($jsonDownloadPath): ?>
        <div class="json-area">
            <h3>Gefundene Metadaten:</h3>
            <a href="<?php echo $jsonDownloadPath; ?>" download class="btn" style="background: #28a745;">Metadaten als .JSON exportieren</a>
            <div class="json-box">
                <pre><?php echo htmlspecialchars(json_encode($exifData, JSON_PRETTY_PRINT)); ?></pre>
            </div>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !$exifData): ?>
        <p><i>Keine EXIF-Metadaten im Original gefunden.</i></p>
    <?php endif; ?>

</body>
</html>