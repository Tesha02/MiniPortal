<?php

function ensureTodos()
{
    if (!isset($_SESSION['todos'])) {
        $_SESSION['todos'] = [];
        $_SESSION['todo_next_id'] = 1;
    }
    if (!isset($_SESSION["todo_next_id"])) {
        $_SESSION["todo_next_id"] = 1;
    }
}

function addTodo(string $s)
{
    ensureTodos();
    $text = trim($s);
    if ($text === "")
        return;
    $id = (int) $_SESSION["todo_next_id"];
    $_SESSION["todo_next_id"] = $id + 1;
    $_SESSION['todos'][] = [
        'id' => $id,
        'text' => $text,
        'done' => false
    ];
}

function deleteTodo(int $id)
{
    ensureTodos();
    $new = [];
    foreach ($_SESSION['todos'] as $t) {
        if ((int) $t['id'] !== $id) {
            $new[] = $t;
        }
    }
    $_SESSION['todos'] = $new;
}

function toggleTodo(int $id)
{
    ensureTodos();
    foreach ($_SESSION['todos'] as &$t) {
        if ((int) $t['id'] === $id) {
            $t['done'] = !$t['done'];
            break;
        }
    }
    unset($t);
}

?>