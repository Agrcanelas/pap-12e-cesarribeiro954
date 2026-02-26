<?php
// 1. Configurações de Erros e Sessão
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// 2. Ligar à Base de Dados
require_once 'auth/config.php'; 

// 3. Verificar o ID do Produto e o User
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// IMPORTANTE: Para gravar na DB, precisamos de um user_id.
// Se o utilizador não estiver logado, vamos usar o ID 0 ou redirecionar.
if (!isset($_SESSION['user_id'])) {
    // Opção A: Forçar login para adicionar ao carrinho
    header("Location: auth/login.php?msg=precisa_logar");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($product_id > 0) {
    // 4. Lógica de Inserção na Base de Dados
    // Se o produto já existir para este user, aumenta a quantidade. 
    // Se não, insere um novo.
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) 
                            VALUES (?, ?, 1) 
                            ON DUPLICATE KEY UPDATE quantity = quantity + 1");
    
    $stmt->bind_param("ii", $user_id, $product_id);
    
    if ($stmt->execute()) {
        // Grava também na sessão como "backup" se quiseres, 
        // mas o principal agora é a DB.
        header("Location: cart.php");
        exit();
    } else {
        echo "Erro ao gravar na base de dados: " . $conn->error;
    }
} else {
    header("Location: index.php");
    exit();
}