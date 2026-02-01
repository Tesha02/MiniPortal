<?php
require_once "includes/auth.php";
require_once "includes/csrf.php";
require_once "includes/todo.php";

requireLogin();
ensureTodos();

$page = $_GET['page'] ?? 'home';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    checkCsrf();
    $action = $_POST['action'];
    switch ($action) {
        case 'add':
            addTodo($_POST['text'] ?? "");
            redirect("dashboard.php?page=todo");
            break;
        case 'toggle':
            toggleTodo($_POST['id'] ?? 0);
            redirect("dashboard.php?page=todo");
            break;
        case 'delete':
            deleteTodo($_POST['id'] ?? 0);
            redirect("dashboard.php?page=todo");
            break;

    }
}

$token = csrfToken();
$user = $_SESSION['user'];
?>

<!doctype html>
<html lang="sr">

<head>
    <meta charset="utf-8">
    <title>Mini Portal - Dashboard</title>
</head>

<body>
    <h1>Dashboard</h1>
    <p>Dobrodosli <?php echo e($user['name']) ?>!</p>
    <nav style="margin-bottom:12px;">
        <a href="dashboard.php">Home</a>
        <a href="dashboard.php?page=todo">ToDo</a>
        <a href="contact.php">Kontakt</a>
        <a href="upload.php">Upload</a>
        <a href="logout.php">Logout</a>
    </nav>

    <?php if ($page === 'todo'): ?>
        <form method="POST">
            <input type="hidden" name="csrf" value="<?php echo e($token); ?>">
            <input type="hidden" name="action" value="add">
            <input type="text" name="text" placeholder="Novi zadatak...">
            <button type="submit">Dodaj</button>
        </form>
        <ul>
            <?php foreach ($_SESSION['todos'] as $t): ?>
                <li>
                    <?php if ($t["done"]): ?>
                        <s><?php echo e($t["text"]); ?></s>
                    <?php else: ?>
                        <?php echo e($t["text"]); ?>
                    <?php endif; ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="csrf" value="<?php echo e($token); ?>">
                        <input type="hidden" name="action" value="toggle">
                        <input type="hidden" name="id" value="<?php echo (int) $t["id"]; ?>">
                        <button type="submit"><?php echo $t["done"] ? "Undo" : "Done"; ?></button>
                    </form>

                    <form method="POST" style="display:inline;" onsubmit="return confirm('Sigurno?');">
                        <input type="hidden" name="csrf" value="<?php echo e($token); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo (int) $t["id"]; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </li>
            <?php endforeach ?>
        </ul>
    <?php else: ?>
        <p>Dobrodošao u mini portal.</p>
        <p>Idi na <a href="dashboard.php?page=todo">ToDo</a> ili otvori Kontakt/Upload</p>
    <?php endif ?>
</body>

</html>