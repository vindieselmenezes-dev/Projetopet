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

$pets = $controller->listarPorStatus("Encontrado");

require_once "../app/Includes/header.php";
require_once "../app/Includes/menu.php";
?>

<main class="conteudo">

    <h1>❤️ Pets Encontrados</h1>

    <p>Pets que alguém encontrou e está tentando localizar o tutor. Reconheceu algum? Entre em contato pelo telefone informado.</p>

    <table class="tabela-pets">

        <thead>
            <tr>
                <th>Foto</th>
                <th>Nome</th>
                <th>Espécie / Raça</th>
                <th>Cidade</th>
                <th>Tutor</th>
                <th>Contato</th>
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

                    <td><?= htmlspecialchars($pet["tutor_telefone"] ?? 'Não informado'); ?></td>

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
                <td colspan="8">Nenhum pet encontrado reportado no momento.</td>
            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</main>

<?php
require_once "../app/Includes/footer.php";
?>
