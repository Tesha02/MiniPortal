<?php
require_once "includes/auth.php";
require_once "includes/csrf.php";

$errors = [];
$emailValue = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    checkCsrf();

    $emailValue = strtolower(trim($_POST['email'] ?? ""));
    $passwordValue = trim($_POST['password'] ?? "");

    if ($emailValue === "" || $passwordValue === "") {
        $errors[] = "Email i lozinka su obavezni";
    } else {
        if (!checkCredentials($emailValue, $passwordValue)) {
            if (isLocked()) {
                $errors[] = "Pogrešili ste 3 puta. Pokušajte kasnije.";
            } else {
                $errors[] = "Pogresili ste kredencijale";
            }
        } else {
            redirect("dashboard.php");
        }
    }
}
$token = csrfToken();
?>

<!doctype html>
<html lang="sr">

<head>
    <meta charset="utf-8">
    <title>Mini Portal - Login</title>
</head>

<body>
    <?php
    if (!empty($errors)): ?>
        <div style="border:1px solid hsl(0, 100%, 40%);padding:10px;margin-bottom:10px;">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <h1>Login Page</h1>
    <form method="POST" action="index.php">
        <input type="hidden" name="csrf" value=<?php echo e($token) ?>>
        <div>
            <label>Email</label>
            <input type="text" name="email" value=<?php echo e($emailValue) ?>>
        </div>
        <br>
        <div>
            <label>Password</label>
            <input type="password" name="password">
        </div>
        <br>
        <button type="submit">Uloguj se</button>
    </form>
    <p>Demo: djordje@example.com / secret123</p>
</body>

</html>