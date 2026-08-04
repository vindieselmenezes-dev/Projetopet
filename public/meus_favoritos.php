<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../app/Models/Favorito.php';

$usuarioId = (int) $_SESSION['usuario_id'];
$favoritoModel = new Favorito();
$favoritos = $favoritoModel->listarPorUsuario($usuarioId);

require_once '../app/Includes/header.php';
require_once '../app/Includes/menu.php';
?>

<main class="conteudo">

    <h1>⭐ Meus Favoritos</h1>

    <p>Pets que você marcou como favoritos.</p>

    <table class="tabela-pets">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nome</th>
                <th>Espécie / Raça</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($favoritos) > 0): ?>
                <?php foreach ($favoritos as $pet): ?>
                    <tr>
                        <td>
                            <img src="../uploads/pets/<?= htmlspecialchars($pet['foto'] ?? 'sem-foto.png'); ?>" width="70" alt="Pet">
                        </td>
                        <td><?= htmlspecialchars($pet['nome'] ?? ''); ?></td>
                        <td><?= htmlspecialchars(($pet['especie'] ?? '') . ' / ' . ($pet['raca'] ?? '')); ?></td>
                        <td><?= htmlspecialchars($pet['status'] ?? ''); ?></td>
                        <td>
                            <a class="btn-editar" href="pet.php?id=<?= (int) $pet['pet_id']; ?>">Ver detalhes</a>
                            <a class="btn-excluir" href="favoritar.php?pet_id=<?= (int) $pet['pet_id']; ?>&acao=remover">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Você ainda não favoritou nenhum pet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</main>

<?php require_once '../app/Includes/footer.php'; ?>
