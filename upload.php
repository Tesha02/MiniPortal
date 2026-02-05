<?php
require_once __DIR__ . "/includes/auth.php";

$errors = [];
$success = "";
$maxBytes = 2 * 1024 * 1024;
$allowed = ['jpg', 'jpeg', 'png', 'pdf'];
$uploadDir = __DIR__ . "/storage/uploads/";


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Fajl nije poslat";
    } else {
        $tmp = $_FILES['file']['tmp_name'];
        $orig = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];

        if ($size > $maxBytes) {
            $errors[] = "Fajl je prevelik";
        } else {
            $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed, true)) {
                $errors[] = "Nije dobra ekstenzija";
            } else {
                $newName = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
                $dest = $uploadDir . $newName;

                if (!move_uploaded_file($tmp, $dest)) {
                    $errors[] = "Greska prilikom premestanja fajlova";
                } else {
                    $success = "Fajl je uspesno uploadovan: " . $newName;
                }
            }
        }
    }
}

$files = [];
if (is_dir($uploadDir)) {
    foreach (scandir($uploadDir) as $f) {
        if ($f === "." || $f === "..")
            continue;
        $files[] = $f;
    }
}

?>

<!doctype html>
<html lang="sr">

<head>
    <meta charset="utf-8">
    <title>Mini Portal - Upload</title>
</head>

<body>
    <?php if ($success !== ""): ?>
        <div style="border:1px solid green;padding:10px;margin-bottom:10px;">
            <?php echo e($success); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div style="border:1px solid #c00;padding:10px;margin-bottom:10px;">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?php echo e($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <h1>Upload</h1>
    <p><a href="dashboard.php">Nazad</a></p>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <button type="submit">Upload</button>
    </form>

    <h2>Uploadovani fajlovi</h2>
    <ul>
        <?php foreach ($files as $f): ?>
            <li><?php echo e($f); ?></li>
        <?php endforeach; ?>
    </ul>
</body>

</html>