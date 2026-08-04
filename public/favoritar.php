<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../app/Models/Favorito.php';

$usuarioId = (int) $_SESSION['usuario_id'];
$petId = (int) ($_GET['pet_id'] ?? 0);
$acao = $_GET['acao'] ?? 'adicionar';

if ($petId > 0) {
    $favorito = new Favorito();

    if ($acao === 'remover') {
        $favorito->remover($usuarioId, $petId);
    } else {
        $favorito->adicionar($usuarioId, $petId);
    }
}

$voltar = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';
header('Location: ' . $voltar);
exit;
