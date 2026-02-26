<?php
// 1. Iniciar sessão e ligar à base de dados
session_start();
require_once 'auth/config.php';

// 2. Verificar se o utilizador está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// 3. Pegar o ID do produto que queremos remover
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

if ($product_id > 0) {
    // 4. Remover o item da base de dados
    // Só removemos se o produto pertencer ao utilizador logado (Segurança)
    $stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $product_id, $user_id);
    
    if ($stmt->execute()) {
        // Opcional: Se também estiveres a usar sessões como backup, remove aqui:
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

// 5. Voltar para o carrinho
header("Location: cart.php");
exit();