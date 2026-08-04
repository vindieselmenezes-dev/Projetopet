<?php

declare(strict_types=1);

session_start();


require_once "../app/Models/Usuario.php";
require_once "../app/Models/LimiteLogin.php";
require_once "../app/Helpers/Csrf.php";

$mensagem = "";
$tipoMensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    

    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    $limiteLogin = new LimiteLogin();

   


    if (!Csrf::validar($_POST["csrf_token"] ?? null)) {

        $mensagem = "Sessão expirada. Atualize a página e tente novamente.";
        $tipoMensagem = "erro";

    } elseif (empty($email) || empty($senha)) {

        $mensagem = "Preencha todos os campos.";
        $tipoMensagem = "erro";

    } elseif ($limiteLogin->estaBloqueado($email)) {

        $minutos = $limiteLogin->minutosRestantes($email);
        $mensagem = "Muitas tentativas incorretas. Tente novamente em {$minutos} minuto(s).";
        $tipoMensagem = "erro";

    } else {

        $usuario = new Usuario();

        $dadosUsuario = $usuario->buscarPorEmail($email);

        if (!$dadosUsuario) {

            $limiteLogin->registrarFalha($email);
            $mensagem = "E-mail ou senha inválidos.";
            $tipoMensagem = "erro";

        } elseif (!password_verify($senha, $dadosUsuario["senha"])) {

            $limiteLogin->registrarFalha($email);

            $restantes = $limiteLogin->tentativasRestantes($email);

            if ($restantes > 0) {
                $mensagem = "E-mail ou senha inválidos. Você tem mais {$restantes} tentativa(s) antes do bloqueio temporário.";
            } else {
                $mensagem = "E-mail ou senha inválidos. Conta bloqueada temporariamente por excesso de tentativas.";
            }

            $tipoMensagem = "erro";

        } else {

            $limiteLogin->registrarSucesso($email);

            $_SESSION["usuario_id"] = $dadosUsuario["id"];
            $_SESSION["usuario_nome"] = $dadosUsuario["nome"];
            $_SESSION["usuario_email"] = $dadosUsuario["email"];
            $_SESSION["perfil_tipo"] = $dadosUsuario["perfil_tipo"] ?? 'cliente';

            header("Location: dashboard.php");
            exit;

        }

    }

}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login - PetFinder Brasil</title>

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

<h2>Entrar</h2>

<?php if (!empty($mensagem)): ?>

<div class="mensagem <?php echo $tipoMensagem; ?>">

<?php echo htmlspecialchars($mensagem); ?>

</div>

<?php endif; ?>

<form method="POST" action="login.php">

<?= Csrf::campoHtml() ?>

<div class="grupo-form">

<label>E-mail</label>

<input
type="email"
name="email"
required>

</div>

<div class="grupo-form">

<label>Senha</label>

<input
type="password"
name="senha"
required>

</div>

<div class="grupo-form">

<button class="btn" type="submit">

Entrar

</button>

</div>

</form>

<p style="text-align:center;margin-top:20px;">

Não possui conta?

<a href="cadastro.php">

Cadastre-se

</a>

</p>

</section>

</main>

</body>

</html>
