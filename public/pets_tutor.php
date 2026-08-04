<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../app/Controllers/PetController.php";
require_once "../app/Models/Favorito.php";

$controller = new PetController();
$favoritoModel = new Favorito();

$pets = $controller->listarPorStatus("Com Tutor");

require_once "../app/Includes/header.php";
require_once "../app/Includes/menu.php";
?>

<main class="conteudo">

    <h1>🏡 Pets Com Tutor</h1>

    <p>Pets cadastrados na plataforma que já têm um tutor e não estão em nenhuma ocorrência (não estão perdidos, não foram encontrados por outra pessoa, e não estão para adoção).</p>

    <table class="tabela-pets">

        <thead>
            <tr>
                <th>Foto</th>
                <th>Nome</th>
                <th>Espécie / Raça</th>
                <th>Cidade</th>
                <th>Tutor</th>
                <th>Data</th>
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

                    <td><?= htmlspecialchars(($pet["especie"] ?? '') . ' / ' . ($pet["raca"] ?? '')); ?></td>

                    <td><?= htmlspecialchars($pet["cidade"] ?? 'Não informada'); ?></td>

                    <td><?= htmlspecialchars($pet["tutor_nome"] ?? ''); ?></td>

                    <td><?= date("d/m/Y", strtotime($pet["criado_em"])); ?></td>
                    <td>
                        <?php if ($favoritoModel->existe((int) $_SESSION['usuario_id'], (int) $pet['id'])): ?>
                            <a class="btn btn-danger btn-sm" href="favoritar.php?pet_id=<?= (int) $pet['id']; ?>&acao=remover">⭐ Remover</a>
                        <?php else: ?>
                            <a class="btn btn-warning btn-sm text-dark" href="favoritar.php?pet_id=<?= (int) $pet['id']; ?>&acao=adicionar">☆ Favoritar</a>
                        <?php endif; ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="7">Nenhum pet com esse status no momento.</td>
            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</main>

<?php
require_once "../app/Includes/footer.php";
?>
