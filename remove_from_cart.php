<?php
// 1. Iniciar sessão e ligar à base de dados
session_start();
require_once 'auth/config.php';

// 2. Verificar se o utilizador está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// 3. Pegar o ID que vem do link (que no cart.php enviamos como cart_id)
$id_a_remover = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

if ($id_a_remover > 0) {
    // 4. MUDANÇA AQUI: Removemos pelo ID da linha do carrinho, que é mais seguro
    // Se a tua coluna na tabela cart se chamar 'id', o código abaixo está correto.
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id_a_remover, $user_id);
    
    if ($stmt->execute()) {
        // Se usas sessões, limpamos também (embora a base de dados seja o principal)
        if (isset($_SESSION['cart'][$id_a_remover])) {
            unset($_SESSION['cart'][$id_a_remover]);
        }
    }
}

// 5. Voltar para o carrinho
header("Location: cart.php");
exit();