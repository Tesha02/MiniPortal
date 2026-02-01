<?php
session_start();
function getDemoUser(): array
{
    return [
        "email" => "djordje@example.com",
        "password" => "secret123",
        "name" => "Djordje"
    ];
}

function ensureCounter(): void
{
    if (!isset($_SESSION['counter'])) {
        $_SESSION['counter'] = 0;
    }
}

function isLocked(): bool
{
    ensureCounter();
    if ($_SESSION['counter'] >= 3) {
        return true;
    }
    return false;
}

function checkCredentials(string $email, string $pass): bool
{
    ensureCounter();

    if (isLocked()) {
        return false;
    }

    $u = getDemoUser();
    if ($u['email'] !== $email || $u['password'] !== $pass) {
        $_SESSION['counter']++;
        return false;
    }

    $_SESSION['user'] = [
        'email' => $u['email'],
        'name' => $u['name']
    ];
    $_SESSION['counter'] = 0;
    return true;
}

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

function requireLogin() {
    if(!isset($_SESSION['user'])) {
        header("Location: index.php");
        exit;
    }
}

?>