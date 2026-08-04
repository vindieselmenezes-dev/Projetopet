<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../app/Models/Pet.php";

$pet = new Pet();

$pets = $pet->listarPorUsuario((int) $_SESSION["usuario_id"]);

$mensagem     = "";
$tipoMensagem = "";

if (!empty($_SESSION["sucesso_pet"])) {
    $mensagem     = $_SESSION["sucesso_pet"];
    $tipoMensagem = "sucesso";
    unset($_SESSION["sucesso_pet"]);
} elseif (!empty($_SESSION["erro_pet"])) {
    $mensagem     = $_SESSION["erro_pet"];
    $tipoMensagem = "erro";
    unset($_SESSION["erro_pet"]);
}

require_once "../app/Includes/header.php";
require_once "../app/Includes/menu.php";
?>

<main class="conteudo">

    <h1>🐶 Meus Pets</h1>

    <p>Todos os animais cadastrados por você.</p>

    <?php if (!empty($mensagem)): ?>

        <div class="mensagem <?= $tipoMensagem; ?>">
            <?= htmlspecialchars($mensagem); ?>
        </div>

    <?php endif; ?>

    <table class="tabela-pets">

        <thead>

            <tr>

                <th>Foto</th>
                <th>Nome</th>
                <th>Espécie</th>
                <th>Cidade</th>
                <th>Status</th>
                <th>Cadastro</th>
                <th>Ações</th>

            </tr>

        </thead>

        <tbody>

        <?php if (count($pets) > 0): ?>

            <?php foreach ($pets as $pet): ?>

                <tr>

                    <td>

                        <img
                            src="../uploads/pets/<?= htmlspecialchars($pet['foto'] ?? 'sem-foto.png'); ?>"
                            width="70"
                            alt="Pet">

                    </td>

                    <td><?= htmlspecialchars($pet["nome"] ?? ''); ?></td>

                    <td><?= htmlspecialchars($pet["especie"] ?? ''); ?></td>

                    <td><?= htmlspecialchars($pet["cidade"] ?? 'Não informada'); ?></td>

                    <td>
                        <span class="badge-status badge-<?= strtolower(str_replace(' ', '-', $pet["status"] ?? 'com-tutor')); ?>">
                            <?= htmlspecialchars($pet["status"] ?? 'Com Tutor'); ?>
                        </span>
                    </td>

                    <td><?= date("d/m/Y", strtotime($pet["criado_em"])); ?></td>

                    <td>

                        <a class="btn-editar"
                           href="editar_pet.php?id=<?= $pet["id"]; ?>">
                            ✏️ Editar
                        </a>

                        <a class="btn-excluir"
                           href="excluir_pet.php?id=<?= $pet["id"]; ?>"
                           onclick="return confirm('Deseja excluir este pet?');">
                            🗑 Excluir
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>

                <td colspan="7">

                    Você ainda não cadastrou nenhum pet.

                </td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</main>

<?php
require_once "../app/Includes/footer.php";
?>
