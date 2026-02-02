<?php
$errors = [];
$ime = "";
$email = "";
$poruka = "";
$success = false;
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $ime = trim($_POST['name']);
    $email = trim($_POST['email']);
    $poruka = trim($_POST['message']);

    if (strlen($ime) < 3) {
        $errors[] = "Ime mora imati min 3 karaktera.";
    }
    if (strlen($poruka) < 10) {
        $errors[] = "Poruka mora imati min 10 karaktera.";
    }
    if (substr_count($email, "@") !== 1) {
        $errors[] = "Email je nevalidan";
    } else {
        $atPos = strpos($email, "@");
        $dotPos = strpos($email, ".");
        if ($dotPos === false || $dotPos < $atPos + 2) {
            $errors[] = "Email je nevalidan";
        }
    }

    if (empty($errors)) {
        $line = date("Y-m-d H-i-s") . " | " . $ime . " | " . str_replace(["\n", "\r"], " ", $poruka) . "<\n>";
        file_put_contents("storage/contacts.txt", $line, FILE_APPEND);
        $ime = $email = $poruka = "";
        $success = true;
    }

}

?>


<!doctype html>
<html lang="sr">

<head>
    <meta charset="utf-8">
    <title>Mini Portal - Contact</title>
</head>

<body>
    <h1>Kontakt</h1>
    <p><a href="dashboard.php">Nazad</a></p>
    <div>

        <?php if (!$success):
            foreach ($errors as $err): ?>
                <ul>
                    <li>
                        <?php echo $err ?>
                    </li>
                </ul>
            <?php endforeach;
        else:
            echo "<p>Uspesno poslato!</p>";
        endif;
        ?>
    </div>

    <form method="POST">
        <div>
            <label>Ime</label><br>
            <input name="name" type="text" value="<?php echo $ime ?>">
        </div>
        <br>
        <div>
            <label>Email</label><br>
            <input name="email" type="text" value="<?php echo $email ?>">
        </div>
        <br>
        <div>
            <label>Poruka</label><br>
            <textarea name="message" rows="5" cols="40"><?php echo $ime ?></textarea>
        </div>
        <br>
        <button type="submit">Posalji</button>

    </form>

</body>

</html>