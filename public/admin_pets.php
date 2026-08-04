<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['perfil_tipo'] ?? '') !== 'administrador') {
    header('Location: login.php');
    exit;
}

require_once '../app/Controllers/PetController.php';

$petController = new PetController();
$pets = $petController->listarTodos();

$mensagem = '';
$tipoMensagem = '';

if (isset($_GET['delete_id'])) {
    $deleteId = (int) $_GET['delete_id'];

    if ($deleteId > 0) {
        if ($petController->excluirPorId($deleteId)) {
            $mensagem = 'Pet removido com sucesso.';
            $tipoMensagem = 'sucesso';
            $pets = $petController->listarTodos();
        } else {
            $mensagem = 'Não foi possível remover o pet.';
            $tipoMensagem = 'erro';
        }
    }
}

require_once '../app/Includes/header.php';
require_once '../app/Includes/menu.php';
?>

<main class="conteudo">

    <h1>🐾 Gestão de Pets</h1>

    <p>Gerencie todos os pets cadastrados na plataforma.</p>

    <?php if ($mensagem): ?>
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
                <th>Raça</th>
                <th>Status</th>
                <th>Tutor</th>
                <th>Cadastro</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($pets) > 0): ?>
                <?php foreach ($pets as $pet): ?>
                    <tr>
                        <td>
                            <img src="../uploads/pets/<?= htmlspecialchars($pet['foto'] ?? 'sem-foto.png'); ?>" width="70" alt="Pet">
                        </td>
                        <td><?= htmlspecialchars($pet['nome']); ?></td>
                        <td><?= htmlspecialchars($pet['especie']); ?></td>
                        <td><?= htmlspecialchars($pet['raca']); ?></td>
                        <td>
                            <span class="badge-status badge-<?= strtolower(str_replace(' ', '-', $pet['status'] ?? 'com-tutor')); ?>">
                                <?= htmlspecialchars($pet['status']); ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($pet['tutor_nome'] . ' (' . $pet['tutor_email'] . ')'); ?></td>
                        <td><?= date('d/m/Y', strtotime($pet['criado_em'])); ?></td>
                        <td>
                            <a class="btn-excluir" href="admin_pets.php?delete_id=<?= $pet['id']; ?>" onclick="return confirm('Deseja excluir este pet?');">🗑 Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Nenhum pet encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</main>

<?php require_once '../app/Includes/footer.php'; ?>