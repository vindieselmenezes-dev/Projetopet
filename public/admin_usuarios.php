<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['perfil_tipo'] ?? '') !== 'administrador') {
    header('Location: login.php');
    exit;
}

require_once '../app/Models/Usuario.php';

$usuarioModel = new Usuario();
$usuarios = $usuarioModel->listarTodos();

$mensagem = '';
$tipoMensagem = '';

if (isset($_GET['delete_id'])) {
    $deleteId = (int) $_GET['delete_id'];

    if ($deleteId > 0) {
        if ($usuarioModel->deletar($deleteId)) {
            $mensagem = 'Usuário removido com sucesso.';
            $tipoMensagem = 'sucesso';
            $usuarios = $usuarioModel->listarTodos();
        } else {
            $mensagem = 'Não foi possível remover o usuário.';
            $tipoMensagem = 'erro';
        }
    }
}

require_once '../app/Includes/header.php';
require_once '../app/Includes/menu.php';
?>

<main class="conteudo">

    <h1>👥 Gestão de Usuários</h1>

    <p>Lista completa de usuários cadastrados no sistema.</p>

    <?php if ($mensagem): ?>
        <div class="mensagem <?= $tipoMensagem; ?>">
            <?= htmlspecialchars($mensagem); ?>
        </div>
    <?php endif; ?>

    <table class="tabela-pets">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Perfil</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($usuarios) > 0): ?>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars(($usuario['nome'] ?? '') . ' ' . ($usuario['sobrenome'] ?? '')); ?></td>
                        <td><?= htmlspecialchars($usuario['email'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($usuario['telefone'] ?? 'Não informado'); ?></td>
                        <td><?= htmlspecialchars($usuario['perfil'] ?? 'cliente'); ?></td>
                        <td>
                            <?php if ($usuario['id'] !== $_SESSION['usuario_id']): ?>
                                <a class="btn-excluir" href="admin_usuarios.php?delete_id=<?= $usuario['id']; ?>" onclick="return confirm('Deseja remover este usuário?');">🗑 Excluir</a>
                            <?php else: ?>
                                <span class="badge-status badge-warning">Ativo</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nenhum usuário encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</main>

<?php require_once '../app/Includes/footer.php'; ?>
