<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../app/Controllers/EmpresaController.php";

$controller = new EmpresaController();
$usuarioId  = (int) $_SESSION["usuario_id"];

$empresas = $controller->listarPorUsuario($usuarioId);

$mensagem     = "";
$tipoMensagem = "";

if (!empty($_SESSION["sucesso_empresa"])) {
    $mensagem     = $_SESSION["sucesso_empresa"];
    $tipoMensagem = "sucesso";
    unset($_SESSION["sucesso_empresa"]);
} elseif (!empty($_SESSION["erro_empresa"])) {
    $mensagem     = $_SESSION["erro_empresa"];
    $tipoMensagem = "erro";
    unset($_SESSION["erro_empresa"]);
}

require_once "../app/Includes/header.php";
require_once "../app/Includes/menu.php";
?>

<main class="conteudo">

    <h1>🏢 Minhas Empresas</h1>

    <p>Empresas que você cadastrou na plataforma.</p>

    <?php if (!empty($mensagem)): ?>

        <div class="mensagem <?= $tipoMensagem; ?>">
            <?= htmlspecialchars($mensagem); ?>
        </div>

    <?php endif; ?>

    <div class="mb-3">
        <a href="cadastrar_empresa.php" class="btn btn-success">➕ Cadastrar Nova Empresa</a>
    </div>

    <table class="tabela-pets">

        <thead>
            <tr>
                <th>Logo</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Cidade</th>
                <th>Status</th>
                <th>Avaliação</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>

        <?php if (count($empresas) > 0): ?>

            <?php foreach ($empresas as $empresa): ?>

                <tr>

                    <td>
                        <img
                            src="<?= !empty($empresa['logo']) ? '../uploads/empresas/' . htmlspecialchars($empresa['logo']) : '../assets/img/pets/sem-foto.png'; ?>"
                            width="60"
                            height="60"
                            style="object-fit:cover; border-radius:8px;"
                            alt="Logo">
                    </td>

                    <td><?= htmlspecialchars($empresa['nome_fantasia'] ?? ''); ?></td>

                    <td><?= htmlspecialchars($empresa['categoria_nome'] ?? ''); ?></td>

                    <td><?= htmlspecialchars(($empresa['cidade'] ?? '') . ($empresa['estado'] ? ' / ' . $empresa['estado'] : '')) ?: 'Não informada'; ?></td>

                    <td>
                        <?php if ((int) $empresa['ativo'] === 1): ?>
                            <span class="badge-status badge-com-tutor">Ativa</span>
                        <?php else: ?>
                            <span class="badge-status badge-perdido">Inativa</span>
                        <?php endif; ?>

                        <?php if (!empty($empresa['verificada'])): ?>
                            <span class="badge-status badge-para-adocao">✔ Verificada</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?= $empresa['avaliacao'] > 0 ? '⭐ ' . number_format((float) $empresa['avaliacao'], 1) : 'Sem avaliações'; ?>
                    </td>

                    <td>

                        <a class="btn-editar" href="editar_empresa.php?id=<?= (int) $empresa['id']; ?>">✏️ Editar</a>

                        <a class="btn-editar" href="meus_produtos.php?empresa_id=<?= (int) $empresa['id']; ?>">📦 Produtos</a>

                        <a class="btn-excluir"
                           href="excluir_empresa.php?id=<?= (int) $empresa['id']; ?>"
                           onclick="return confirm('Deseja excluir esta empresa? Essa ação não pode ser desfeita.');">
                           🗑 Excluir
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="7">Você ainda não cadastrou nenhuma empresa.</td>
            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</main>

<?php
require_once "../app/Includes/footer.php";
?>
