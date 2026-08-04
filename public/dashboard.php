<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../app/Controllers/PetController.php";
require_once "../app/Models/Usuario.php";

$petController = new PetController();
$usuario       = new Usuario();

$totalPets        = $petController->contarPets();
$totalUsuarios    = $usuario->contarUsuarios();
$totalPerdidos    = $petController->contarPorStatus("Perdido");
$totalEncontrados = $petController->contarPorStatus("Encontrado");
$totalAdocao      = $petController->contarPorStatus("Para Adoção");
$totalComTutor    = $petController->contarPorStatus("Com Tutor");
$totalAdotados    = $petController->contarPorStatus("Adotado");

require_once "../app/Includes/header.php";
require_once "../app/Includes/menu.php";
?>

<main class="conteudo">

    <h1>📊 Dashboard</h1>

    <div class="cards">

        <a href="meus_pets.php" class="card">
            <div class="icone">🐶</div>
            <h3>Pets cadastrados</h3>
            <div class="numero">
                <?= $totalPets; ?>
            </div>
        </a>

        <div class="card">
            <div class="icone">👤</div>
            <h3>Usuários</h3>
            <div class="numero">
                <?= $totalUsuarios; ?>
            </div>
        </div>

        <a href="pets_perdidos.php" class="card">
            <div class="icone">🔍</div>
            <h3>Pets Perdidos</h3>
            <div class="numero">
                <?= $totalPerdidos; ?>
            </div>
        </a>

        <a href="pets_encontrados.php" class="card">
            <div class="icone">❤️</div>
            <h3>Pets Encontrados</h3>
            <div class="numero">
                <?= $totalEncontrados; ?>
            </div>
        </a>

        <a href="pets_adocao.php" class="card">
            <div class="icone">🏠</div>
            <h3>Para Adoção</h3>
            <div class="numero">
                <?= $totalAdocao; ?>
            </div>
        </a>

        <a href="pets_tutor.php" class="card">
            <div class="icone">🏡</div>
            <h3>Com Tutor</h3>
            <div class="numero">
                <?= $totalComTutor; ?>
            </div>
        </a>

        <a href="pets_adotados.php" class="card">
            <div class="icone">🎉</div>
            <h3>Adotados</h3>
            <div class="numero">
                <?= $totalAdotados; ?>
            </div>
        </a>

    </div>

</main>

<?php
require_once "../app/Includes/footer.php";
?>
