<?php
function csrfToken():string {
    if(!isset($_SESSION['csrf'])) {
        $_SESSION['csrf']=bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function checkCsrf(): void {
    $posted=$_POST['csrf'] ?? "";
    $sess=$_SESSION['csrf'] ?? "";
    if($posted==="" || $sess==="" || $posted!==$sess) {
        echo "Nevalidan zahtev";
        exit;
    }
}

function redirect(string $str): void {
    header("Location: ".$str);
    exit;
}

?>