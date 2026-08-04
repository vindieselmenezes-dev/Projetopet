<?php

declare(strict_types=1);

session_start();

require_once "../app/Models/Usuario.php";
require_once "../app/Helpers/ValidacaoSenha.php";
require_once "../app/Helpers/Csrf.php";

$mensagem = "";
$tipoMensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    

    $nome            = trim($_POST["nome"] ?? "");
    $sobrenome       = trim($_POST["sobrenome"] ?? "");
    $email           = trim($_POST["email"] ?? "");
    $telefone        = trim($_POST["telefone"] ?? "");
    $senha           = $_POST["senha"] ?? "";
    $confirmarSenha  = $_POST["confirmar_senha"] ?? "";

    $erroSenha = ValidacaoSenha::validar($senha);

    if (!Csrf::validar($_POST["csrf_token"] ?? null)) {

        $mensagem = "Sessão expirada. Atualize a página e tente novamente.";
        $tipoMensagem = "erro";

    } elseif (
        empty($nome) ||
        empty($sobrenome) ||
        empty($email) ||
        empty($senha)
    ) {

        $mensagem = "Preencha todos os campos obrigatórios.";
        $tipoMensagem = "erro";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $mensagem = "Informe um e-mail válido.";
        $tipoMensagem = "erro";

    } elseif ($erroSenha !== null) {

        $mensagem = $erroSenha;
        $tipoMensagem = "erro";

    } elseif ($senha !== $confirmarSenha) {

        $mensagem = "As senhas não conferem.";
        $tipoMensagem = "erro";

    } else {

        $usuario = new Usuario();

        $resultado = $usuario->emailExiste($email);

        

        if ($usuario->emailExiste($email)) {

            $mensagem = "Este e-mail já está cadastrado.";
            $tipoMensagem = "erro";

        } else {

            $dados = [

                "nome" => $nome,
                "sobrenome" => $sobrenome,
                "email" => $email,
                "telefone" => $telefone,
                "senha" => $senha

            ];

            if ($usuario->cadastrar($dados)) {

                $mensagem = "Cadastro realizado com sucesso!";
                $tipoMensagem = "sucesso";

            } else {

                $mensagem = "Erro ao cadastrar usuário.";
                $tipoMensagem = "erro";

            }

        }

    }

}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro - PetFinder Brasil</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<header class="cabecalho">

    <div class="container">

        <h1><a href="../index.html">PetFinder Brasil</a></h1>

        <p>Informação, cuidado e carinho para seu pet.</p>

    </div>

</header>

<main class="container">

<section class="formulario-cadastro">

<h2>Criar Conta</h2>

<?php if (!empty($mensagem)): ?>

<div class="mensagem <?php echo $tipoMensagem; ?>">

    <?php echo htmlspecialchars($mensagem); ?>

</div>

<?php endif; ?>

<form method="POST" action="">

<?= Csrf::campoHtml() ?>

    <div class="grupo-form">
        <label for="nome">Nome *</label>
        <input
            type="text"
            id="nome"
            name="nome"
            maxlength="150"
            required
            value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>">
    </div>

    <div class="grupo-form">
        <label for="sobrenome">Sobrenome *</label>
        <input
            type="text"
            id="sobrenome"
            name="sobrenome"
            maxlength="150"
            required
            value="<?php echo htmlspecialchars($_POST['sobrenome'] ?? ''); ?>">
    </div>

    <div class="grupo-form">
        <label for="email">E-mail *</label>
        <input
            type="email"
            id="email"
            name="email"
            maxlength="180"
            required
            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
    </div>

    <div class="grupo-form">
        <label for="telefone">Telefone</label>
        <input
            type="text"
            id="telefone"
            name="telefone"
            maxlength="20"
            placeholder="(31) 99999-9999"
            value="<?php echo htmlspecialchars($_POST['telefone'] ?? ''); ?>">
    </div>

    <div class="grupo-form">
        <label for="senha">Senha *</label>
        <input
            type="password"
            id="senha"
            name="senha"
            minlength="8"
            required>
        <small style="display:block; color:#6c757d; margin-top:4px;">
            Mínimo 8 caracteres, com 1 letra maiúscula e 1 número.
        </small>
    </div>

    <div class="grupo-form">
        <label for="confirmar_senha">Confirmar Senha *</label>
        <input
            type="password"
            id="confirmar_senha"
            name="confirmar_senha"
            minlength="8"
            required>
    </div>

    <div class="grupo-form">

        <button type="submit" class="btn">

            Cadastrar

        </button>

    </div>

</form>

<p style="margin-top:20px; text-align:center;">

    Já possui uma conta?

    <a href="login.php">

        Fazer Login

    </a>

</p>

</section>

</main>

<footer class="rodape">

    <div class="container">

        <p>

            © <?php echo date("Y"); ?> PetFinder Brasil

            <br>

            Informação, cuidado e carinho para seu pet.

        </p>

    </div>

</footer>

<script src="../assets/js/script.js"></script>

</body>

</html>
