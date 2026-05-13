<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$uploadDir = 'uploads/';
$cleanDir = 'clean/';

// create folders if they don't exist (0777 = max permissions)
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
if (!is_dir($cleanDir)) mkdir($cleanDir, 0777, true);

// prepare empty variables
$message = "";
$exifData = [];
$originalViewPath = "";
$cleanViewPath = "";
$jsonDownloadPath = "";

// check if a form was submitted and an image was uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    
    // save file details to a shorter variable
    $file = $_FILES['image'];
    
    // append timestamp to filename to avoid duplicate names overwriting each other
    $baseName = time() . '_' . basename($file['name']);
    
    // build paths for our files
    $targetFile = $uploadDir . $baseName;
    $cleanFile = $cleanDir . 'clean_' . $baseName;
    $jsonFile = $cleanDir . 'metadata_' . $baseName . '.json';

    // move file to our uploads folder
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        
        $originalViewPath = $targetFile;
        
        // find out the real file type (mime)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($targetFile);

        // step 1: extract metadata (only for jpegs)
        if ($mime === 'image/jpeg') {
            
            // @ hides error messages if the image has no exif data
            $exifData = @exif_read_data($targetFile);
            
            if ($exifData) {
                // save data as a json file
                file_put_contents($jsonFile, json_encode($exifData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $jsonDownloadPath = $jsonFile;
            }
        }

        // clean image and rotate correctly if needed
        $imgResource = null; 
        
        if ($mime === 'image/jpeg') {
            // load original jpeg into memory
            $imgResource = imagecreatefromjpeg($targetFile);
            
            // check if rotation was saved in the extracted metadata
            if (isset($exifData['Orientation'])) {
                // rotate image based on the value (3, 6, or 8)
                switch ($exifData['Orientation']) {
                    case 3:
                        // image is upside down -> rotate 180 degrees
                        $imgResource = imagerotate($imgResource, 180, 0);
                        break;
                    case 6:
                        // image is on its right side -> rotate 90 degrees clockwise (-90)
                        $imgResource = imagerotate($imgResource, -90, 0);
                        break;
                    case 8:
                        // image is on its left side -> rotate 90 degrees counter-clockwise
                        $imgResource = imagerotate($imgResource, 90, 0);
                        break;
                }
            }
            
            imagejpeg($imgResource, $cleanFile, 90);
            
        } elseif ($mime === 'image/png') {
            // load pngs
            $imgResource = imagecreatefrompng($targetFile);
            imagepng($imgResource, $cleanFile, 6);
        }

        // clean up
        if ($imgResource !== null) {
            
            // clean up server memory
            imagedestroy($imgResource); 
            
            // permanently delete original file with old metadata from the database
            if (file_exists($targetFile)) {
                unlink($targetFile); 
            }

            // remember path for the clean image to show the user
            $cleanViewPath = $cleanFile;
            
            $message = "✅ Metadaten erfolgreich entfernt und Originaldatei gelöscht!";
            
        } else {
            $message = "❌ Fehler: Dateityp wird nicht unterstützt. Bitte JPG oder PNG hochladen.";
        }
    } else {
        $message = "❌ Fehler beim Hochladen der Datei.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Metadaten entfernen</title>
    <link rel="stylesheet" href="../../style/main.css">
    <link rel="stylesheet" href="../../style/metadata_stripping.css">
    <link rel="stylesheet" href="../../style/navbar.css">
</head>
<body>

    <?php include '../../template/navbar.php'; ?>
    <div class="content"> 
    <h1>Metadaten entfernen</h1>
    
    <form class="form-container" method="post" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <button type="submit">Metadaten entfernen</button>
    </form>

    <?php if ($message): ?>
        <p style="color: #00ff00;"><?php echo $message; ?></p>
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
    </div>
</body>
</html>