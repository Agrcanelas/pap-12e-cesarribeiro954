<?php
session_start();
require_once 'auth/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Lógica para atualizar a quantidade se vier via GET (setinhas)
if (isset($_GET['update_id']) && isset($_GET['new_qty'])) {
    $cart_id = (int)$_GET['update_id'];
    $new_qty = (int)$_GET['new_qty'];
    if ($new_qty > 0) {
        $stmt_upd = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt_upd->bind_param("iii", $new_qty, $cart_id, $_SESSION['user_id']);
        $stmt_upd->execute();
    }
    header("Location: cart.php"); // Recarrega para atualizar totais
    exit();
}

require_once 'includes/header.php';

$user_id = $_SESSION['user_id'];
$total_carrinho = 0;

$sql = "SELECT p.*, c.quantity, c.id AS cart_id FROM cart c INNER JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    $erro_sql = $conn->error;
} else {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Meu Carrinho | Ecopeças</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .cart-wrapper { padding: 130px 20px 80px; max-width: 900px; margin: auto; min-height: 80vh; }
        .cart-container { background: #fff; padding: 25px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .cart-item { display: flex; align-items: center; padding: 20px 0; border-bottom: 1px solid #eee; }
        .cart-item img { width: 70px; height: 70px; border-radius: 12px; object-fit: cover; }
        .cart-info { flex: 1; margin-left: 20px; }
        .cart-price { font-weight: bold; color: #2e7d32; font-size: 18px; margin-right: 20px; }
        
        /* ESTILO DAS SETINHAS */
        .qty-control { display: flex; align-items: center; gap: 10px; margin-top: 5px; }
        .btn-qty { 
            display: flex; align-items: center; justify-content: center;
            width: 26px; height: 26px; border-radius: 8px; 
            border: 1px solid #ddd; background: #f9fafb; color: #333;
            text-decoration: none; font-weight: bold; transition: 0.2s;
        }
        .btn-qty:hover { background: #2e7d32; color: #fff; border-color: #2e7d32; }
        .qty-num { font-weight: bold; font-size: 15px; min-width: 20px; text-align: center; }

        .btn-remove { color: #ff4d4d; background: #fff5f5; border: 1px solid #ffebeb; padding: 12px; border-radius: 12px; transition: 0.3s; text-decoration: none; }
        .btn-remove:hover { background: #ff4d4d; color: #fff; transform: scale(1.1); }
        .cart-footer-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 30px; gap: 15px; }
        .btn-ver-mais { flex: 1; text-align: center; padding: 16px; border-radius: 15px; text-decoration: none; font-weight: bold; color: #4b5563; background: #f3f4f6; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.3s; }
        .btn-finalizar { flex: 2; text-align: center; background: #2e7d32; color: #fff; padding: 18px; border-radius: 15px; text-decoration: none; font-weight: bold; font-size: 18px; box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3); display: flex; align-items: center; justify-content: center; gap: 10px; transition: 0.3s; }
        .mini-frete-box { margin: 30px 0 20px; padding: 25px; background: #f9fafb; border-radius: 15px; border: 1px dashed #d1d5db; }
        @keyframes celebra { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }
        .msg-sucesso { color: #166534; font-weight: 800; display: flex; align-items: center; justify-content: center; gap: 12px; animation: celebra 0.8s infinite; margin-bottom: 15px; font-size: 18px; }
        .progress-bg-mini { width: 100%; height: 14px; background: #e5e7eb; border-radius: 20px; overflow: hidden; }
        .progress-fill-mini { height: 100%; background: linear-gradient(90deg, #4caf50, #81c784, #2e7d32); background-size: 200% 100%; animation: moveGradient 2s linear infinite; transition: width 0.8s; }
        @keyframes moveGradient { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
        .barra-cheia { box-shadow: 0 0 15px rgba(34, 197, 94, 0.5); background: linear-gradient(90deg, #fbbf24, #22c55e, #fbbf24) !important; }
        .total-box { display: flex; justify-content: space-between; font-size: 26px; font-weight: 800; margin-top: 15px; }
    </style>
</head>
<body>
<div class="cart-wrapper">
    <div class="cart-container">
        <h2 style="margin-bottom:25px;">🛒 O meu Carrinho</h2>
        <?php 
        $items = [];
        if (isset($result) && $result) {
            while($row = $result->fetch_assoc()) {
                $preco = $row['price'] ?? $row['preco'] ?? 0;
                $total_carrinho += ($preco * $row['quantity']);
                $items[] = $row;
            }
        }
        $meta = 250;
        $percentagem = ($total_carrinho > 0) ? ($total_carrinho / $meta) * 100 : 0;
        if($percentagem > 100) $percentagem = 100;
        $falta = $meta - $total_carrinho;
        ?>
        <?php if(empty($items)): ?>
            <div style="text-align:center; padding: 60px;">
                <p style="font-size:18px;">O teu carrinho está vazio, César.</p><br>
                <a href="index.php" class="btn-finalizar" style="display:inline-block; width:auto; padding: 15px 40px;">Ir buscar Peças</a>
            </div>
        <?php else: ?>
            <?php foreach($items as $item): 
                $imagem = $item['image'] ?? $item['imagem'] ?? '';
                $nome = $item['name'] ?? $item['nome'] ?? 'Peça Auto';
                $preco_u = $item['price'] ?? $item['preco'] ?? 0;
            ?>
                <div class="cart-item">
                    <img src="<?= htmlspecialchars($imagem) ?>" onerror="this.src='https://via.placeholder.com/70'">
                    <div class="cart-info">
                        <h4 style="margin:0;"><?= htmlspecialchars($nome) ?></h4>
                        
                        <div class="qty-control">
                            <a href="cart.php?update_id=<?= $item['cart_id'] ?>&new_qty=<?= $item['quantity'] - 1 ?>" class="btn-qty">-</a>
                            <span class="qty-num"><?= $item['quantity'] ?></span>
                            <a href="cart.php?update_id=<?= $item['cart_id'] ?>&new_qty=<?= $item['quantity'] + 1 ?>" class="btn-qty">+</a>
                        </div>
                    </div>
                    <div class="cart-price"><?= number_format($preco_u * $item['quantity'], 2, ',', '.') ?> €</div>
                    <a href="remove_from_cart.php?id=<?= $item['cart_id'] ?>" class="btn-remove"><i class="fa fa-trash-can"></i></a>
                </div>
            <?php endforeach; ?>

            <div class="mini-frete-box">
                <?php if($total_carrinho >= $meta): ?>
                    <div class="msg-sucesso"><i class="fa fa-truck-fast"></i> PORTES GRÁTIS ATIVADOS! <i class="fa fa-truck-fast"></i></div>
                <?php else: ?>
                    <span style="display:block; text-align:center; font-size:15px; margin-bottom:10px;">Faltam <strong><?= number_format($falta, 2, ',', '.') ?>€</strong> para o envio ser grátis!</span>
                <?php endif; ?>
                <div class="progress-bg-mini"><div class="progress-fill-mini <?= ($total_carrinho >= $meta) ? 'barra-cheia' : '' ?>" style="width: <?= $percentagem ?>%;"></div></div>
            </div>

            <div class="total-box"><span>Total:</span><span style="color: #2e7d32;"><?= number_format($total_carrinho, 2, ',', '.') ?> €</span></div>
            
            <div class="cart-footer-actions">
                <a href="index.php#categorias" class="btn-ver-mais"><i class="fa fa-arrow-left"></i> Adicionar mais peças</a>
                <a href="checkout.php" class="btn-finalizar">Finalizar Encomenda <i class="fa fa-check-double"></i></a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
</body>
</html>