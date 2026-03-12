<?php
session_start();
require_once 'auth/config.php';

// 1. Verificar se o utilizador está logado e se temos um ID de produto
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_GET['id'];
$quantity = 1;

// 2. Verificar se o produto já existe no carrinho deste utilizador
$check_sql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Se já existe, somamos +1 à quantidade
    $new_qty = $row['quantity'] + 1;
    $update_sql = "UPDATE cart SET quantity = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $new_qty, $row['id']);
    $update_stmt->execute();
} else {
    // Se não existe, inserimos uma nova linha
    $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $insert_stmt->execute();
}

// 3. Redirecionar para o carrinho para o César ver a barra de progresso!
header("Location: cart.php");
exit();