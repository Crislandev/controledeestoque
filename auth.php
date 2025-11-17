<?php
require 'db.php';

$action = $_GET['action'] ?? '';

if ($action === 'login') {

    $usuario = trim($_POST['usuario'] ?? '');
    $senha   = trim($_POST['senha'] ?? '');

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ? AND senha = ?");
    $stmt->execute([$usuario, $senha]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user'] = $user;
        header("Location: dashboard.php");
        exit;
    } else {
        echo "<script>alert('Usuário ou senha incorretos');window.location='index.php';</script>";
        exit;
    }
}

if ($action === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}
