<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'auth/config.php'; 
require_once 'includes/header.php';

// Variáveis para as traduções (mantendo o teu padrão)
$lang = $_SESSION['lang'] ?? 'pt';

// Se não está logado, cart vazio
if (!isset($_SESSION['user_id'])) {
    $cart_items = array();
} else {
    // Ler diretamente da base de dados
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    $cart_items = array();
    while ($row = $res->fetch_assoc()) {
        $cart_items[$row['product_id']] = $row['quantity'];
    }
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= ($lang == 'pt') ? 'O Meu Carrinho' : 'My Cart' ?> - Ecopeças</title>
    <style>
        .cart-container { padding: 50px 20px; min-height: 70vh; }
        .cart-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .cart-table th { background: #2e7d32; color: #fff; padding: 15px; text-transform: uppercase; font-size: 14px; }
        .cart-table td { padding: 20px; border-bottom: 1px solid #eee; text-align: center; }
        
        body.dark .cart-table { background: #1e1e1e; color: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        body.dark .cart-table td { border-bottom: 1px solid #333; }

        .btn-remove { color: #e74c3c; transition: 0.3s; font-size: 1.2rem; cursor: pointer; text-decoration: none; }
        .btn-remove:hover { transform: scale(1.2); color: #c0392b; }
        
        .checkout-box { margin-top: 30px; text-align: right; background: #f9f9f9; padding: 25px; border-radius: 15px; }
        body.dark .checkout-box { background: #252525; }
    </style>
</head>
<body class="<?= ($_SESSION['theme'] ?? 'light') === 'dark' ? 'dark' : '' ?>">

<div class="container cart-container">
    <h1 style="margin-bottom: 30px; font-weight: 800; color: #2e7d32;">
        <i class="fa fa-shopping-cart"></i> <?= ($lang == 'pt') ? 'Carrinho de Compras' : 'Shopping Cart' ?>
    </h1>

    <?php if (!empty($cart_items)): ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th><?= ($lang == 'pt') ? 'Produto' : 'Product' ?></th>
                    <th><?= ($lang == 'pt') ? 'Preço' : 'Price' ?></th>
                    <th><?= ($lang == 'pt') ? 'Quantidade' : 'Quantity' ?></th>
                    <th>Subtotal</th>
                    <th><?= ($lang == 'pt') ? 'Remover' : 'Remove' ?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Percorrer os IDs que estão na sessão
                foreach ($cart_items as $id => $quantity): 
                    // Ir à base de dados buscar os detalhes deste produto específico
                    $stmt = $conn->prepare("SELECT name, price, image_url FROM products WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    
                    if ($product = $res->fetch_assoc()):
                        $subtotal = $product['price'] * $quantity;
                        $total += $subtotal;
                ?>
                <tr>
                    <td style="display: flex; align-items: center; gap: 15px; text-align: left;">
                        <img src="images/<?= htmlspecialchars($product['image_url']) ?>" style="width: 70px; height: 70px; object-fit: cover; border-radius: 10px;">
                        <span style="font-weight: bold;"><?= htmlspecialchars($product['name']) ?></span>
                    </td>
                    <td><?= number_format($product['price'], 2, ',', '.') ?>€</td>
                    <td>
                        <span style="padding: 5px 15px; background: #eee; border-radius: 20px; color: #333; font-weight: bold;">
                            <?= $quantity ?>
                        </span>
                    </td>
                    <td style="font-weight: bold; color: #2e7d32;"><?= number_format($subtotal, 2, ',', '.') ?>€</td>
                    <td>
                        <a href="remove_from_cart.php?id=<?= $id ?>" class="btn-remove" onclick="return confirm('Remover produto?')">
                            <i class="fa fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
                <?php 
                    endif;
                endforeach; 
                ?>
            </tbody>
        </table>

        <div class="checkout-box">
            <h2 style="margin: 0;">Total: <span style="color: #2e7d32;"><?= number_format($total, 2, ',', '.') ?>€</span></h2>
            
            <div style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 20px;">
                <a href="index.php" style="text-decoration: none; color: #666; padding: 15px 25px;"><?= ($lang == 'pt') ? 'Continuar a Comprar' : 'Continue Shopping' ?></a>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <button style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; border: none; padding: 15px 40px; border-radius: 50px; font-weight: bold; cursor: pointer;">
                        <?= ($lang == 'pt') ? 'Finalizar Encomenda' : 'Checkout' ?>
                    </button>
                <?php else: ?>
                    <a href="auth/login.php" style="background: #66d78b; color: white; border: none; padding: 15px 40px; border-radius: 50px; font-weight: bold; text-decoration: none;">
                        <?= ($lang == 'pt') ? 'Login para Comprar' : 'Login to Checkout' ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>
        <div style="text-align: center; padding: 60px;">
            <i class="fa fa-shopping-basket" style="font-size: 5rem; color: #ddd; margin-bottom: 20px;"></i>
            <h2><?= ($lang == 'pt') ? 'O seu carrinho está vazio' : 'Your cart is empty' ?></h2>
            <br>
            <a href="index.php" style="background: #2e7d32; color: white; padding: 12px 30px; border-radius: 50px; text-decoration: none; font-weight: bold;">
                <?= ($lang == 'pt') ? 'Ver Produtos' : 'View Products' ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>