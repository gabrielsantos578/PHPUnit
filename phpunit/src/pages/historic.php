<?php
namespace App\Pages;

use App\Database\Database;

// Função para verificar se o usuário está logado
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Função para obter todas as sessões
function getAllSessions() {
    $database = new Database();
    $response = $database->select("SELECT * FROM Sessions");
    return $response->data;
}

// Função para obter todos os usuários
function getAllUsers() {
    $database = new Database();
    $response = $database->select("SELECT * FROM Users");
    return $response->data;
}

// Função para obter as sessões ativas
function getActiveSessions() {
    $database = new Database();
    $response = $database->select("SELECT * FROM Sessions WHERE dateClosure IS NULL");
    return $response->data;
}

// Função para obter o histórico de sessões
function getSessionHistory() {
    $database = new Database();
    $response = $database->select("SELECT * FROM Sessions WHERE dateClosure IS NOT NULL");
    return $response->data;
}

// Função para obter o nome de usuário pelo ID de usuário
function getUserName($userId) {
    $database = new Database();
    $response = $database->select("SELECT username FROM Users WHERE id = $userId");

    if ($response->status && count($response->data) > 0) {
        return $response->data[0]['username'];
    }

    return null;
}

// Função para verificar se o usuário está ativo
function isUserActive($userId) {
    $database = new Database();
    $response = $database->select("SELECT id FROM Sessions WHERE idUser = $userId AND dateClosure IS NULL");

    return $response->status && count($response->data) > 0;
}

// Inicializa a sessão
session_start();

// Obtém o ID do usuário da sessão
$loggedInUserId = $_SESSION['user_id'] ?? null;

// Obtém o nome do usuário logado
$loggedInUserName = getUserName($loggedInUserId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h2>Bem-vindo, <?php echo $loggedInUserName; ?>!</h2>

    <h2>Usuários Online</h2>
    <ul>
        <?php $activeSessions = getActiveSessions(); ?>
        <?php foreach ($activeSessions as $session) : ?>
            <?php $userId = $session['idUser']; ?>
            <?php $userName = getUserName($userId); ?>
            <li><?php echo $userName; ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Usuários Offline</h2>
    <ul>
        <?php $allUsers = getAllUsers(); ?>
        <?php foreach ($allUsers as $user) : ?>
            <?php $userId = $user['id']; ?>
            <?php if (!isUserActive($userId)) : ?>
                <li><?php echo $user['username']; ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <h2>Histórico de Sessões</h2>
    <ul>
        <?php $sessionHistory = getSessionHistory(); ?>
        <?php foreach ($sessionHistory as $session) : ?>
            <li>Abertura: <?php echo $session['dateOpening']; ?> - Fechamento: <?php echo $session['dateClosure']; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
