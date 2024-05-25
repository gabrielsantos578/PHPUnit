<?php declare(strict_types=1);
namespace App\Pages;

use App\Database\Connection;

// Inicializa a sessão
session_start();

// Função para verificar se o usuário está logado
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Função para redirecionar para outra página
function redirectTo($page) {
    header("Location: $page");
    exit;
}

// Verifica se os dados do formulário foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o email e a senha foram enviados
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Recupera o email e a senha do formulário
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Verifica se o email e a senha correspondem a um usuário no banco de dados
        $conn = new Connection();
        $response = $conn->select("SELECT id FROM Users WHERE email = '$email' AND password = '$password'");

        // Se o login for bem-sucedido, insere a sessão no banco de dados
        if ($response->status && count($response->data) > 0) {
            $userId = $response->data[0]['id'];

            // Insere a sessão no banco de dados
            $dateOpening = date('d/m/Y H:i:s');
            $dateClosure = null; // A data de fechamento é definida como null inicialmente
            $conn->insert("INSERT INTO Sessions (idUser) VALUES ($userId)");

            // Define o ID do usuário na sessão
            $_SESSION['user_id'] = $userId;

            // Redireciona para a outra página
            redirectTo("outra_pagina.php");
        } else {
            // Se o login falhar, define uma mensagem de erro
            $error = "Email ou senha inválidos.";
        }
    }
}

// Se os dados do formulário não foram enviados via POST ou o login falhou, exibe o formulário de login
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
