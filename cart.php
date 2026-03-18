<?php
session_start();
require_once 'auth/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

if (isset($_GET['update_id']) && isset($_GET['new_qty'])) {
    $cart_id = (int)$_GET['update_id'];
    $new_qty = (int)$_GET['new_qty'];
    if ($new_qty > 0) {
        $stmt_upd = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt_upd->bind_param("iii", $new_qty, $cart_id, $_SESSION['user_id']);
        $stmt_upd->execute();
    }
    header("Location: cart.php"); 
    exit();
}

require_once 'includes/header.php';

$user_id = $_SESSION['user_id'];
$total_carrinho = 0;
$total_poupanca = 0; // Nova variável para somar a poupança total

$sql = "SELECT p.*, c.quantity, c.id AS cart_id FROM cart c INNER JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$items = [];

while($row = $result->fetch_assoc()) {
    $preco_original = $row['price'] ?? $row['preco'] ?? 0;
    $preco_final = $preco_original;
    
    // Se estiver em oferta, calcular a poupança
    if (isset($row['em_oferta']) && $row['em_oferta'] == 1 && isset($row['preco_antigo']) && $row['preco_antigo'] > 0) {
        // O 'price' já é o preço com desconto, o 'preco_antigo' é o original
        $poupanca_unidade = $row['preco_antigo'] - $preco_original;
        $total_poupanca += ($poupanca_unidade * $row['quantity']);
    }

    $total_carrinho += ($preco_original * $row['quantity']);
    $items[] = $row;
}

$meta = 250;
$percentagem = ($total_carrinho > 0) ? ($total_carrinho / $meta) * 100 : 0;
if($percentagem > 100) $percentagem = 100;
$falta = $meta - $total_carrinho;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O meu Carrinho | Ecopeças Premium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --p-green: #2e7d32;
            --p-green-light: #e8f5e9;
            --p-dark: #121212;
            --bg-body: #f3f4f6;
            --p-red: #d32f2f;
            --p-offer: #e53935;
        }

        body { 
            background-color: var(--bg-body);
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            color: var(--p-dark);
        }

        .main-wrapper { 
            padding: 160px 5% 80px; 
            max-width: 1300px; 
            margin: 0 auto;
        }

        .cart-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
            align-items: start;
        }

        .cart-grid.is-empty { display: block; }

        .cart-items-wrapper {
            background: #ffffff;
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        }

        .cart-items-wrapper h1 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 35px;
            display: flex;
            align-items: center;
            gap: 15px;
            letter-spacing: -1px;
        }

        .premium-card {
            display: flex;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid #f0f0f0;
            transition: 0.3s ease;
        }
        .premium-card:last-child { border-bottom: none; }

        .prod-img {
            width: 100px; height: 100px;
            border-radius: 18px;
            object-fit: cover;
            background: #f9f9f9;
        }

        .prod-details { flex: 1; padding: 0 20px; }
        .prod-details h4 { margin: 0 0 5px; font-size: 18px; font-weight: 700; color: var(--p-dark); }
        .prod-details span { color: #888; font-size: 13px; font-weight: 600; }
        
        .badge-offer-mini {
            background: var(--p-offer);
            color: #fff;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 800;
            margin-left: 5px;
            vertical-align: middle;
        }

        .qty-control {
            display: flex;
            align-items: center;
            background: #f5f5f5;
            border-radius: 12px;
            padding: 4px;
        }
        .qty-btn {
            width: 32px; height: 32px;
            background: #fff; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; color: #000; font-weight: 800;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .qty-num { padding: 0 12px; font-weight: 700; color: var(--p-green); }

        .prod-price { font-size: 20px; font-weight: 800; min-width: 130px; text-align: right; color: var(--p-dark); }
        .price-discounted { color: var(--p-offer); }
        .old-price-cart { font-size: 14px; text-decoration: line-through; color: #bbb; display: block; font-weight: 600; }

        .remove-icon {
            margin-left: 20px;
            color: var(--p-red);
            font-size: 22px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff5f5;
        }
        .remove-icon:hover { color: #b71c1c; transform: scale(1.1); background: #ffebee; }

        /* Sidebar & Progress Bar */
        .summary-sidebar {
            background: #ffffff;
            color: var(--p-dark);
            padding: 35px;
            border-radius: 30px;
            position: sticky;
            top: 150px;
            border: 2px solid var(--p-green);
            box-shadow: 0 15px 40px rgba(46, 125, 50, 0.08);
        }

        .shipping-box-premium { margin-bottom: 30px; background: var(--p-green-light); padding: 15px; border-radius: 15px; position: relative; }
        .shipping-info { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px; font-weight: 700; }
        
        .progress-container { width: 100%; height: 8px; background: rgba(0,0,0,0.05); border-radius: 10px; overflow: hidden; }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #4caf50, #81c784, #2e7d32);
            transition: width 0.8s ease;
        }

        .total-row-premium {
            display: flex; justify-content: space-between; align-items: baseline;
            margin: 15px 0;
        }
        .total-row-premium span { font-size: 16px; font-weight: 600; color: #666; }
        .total-row-premium b { font-size: 34px; font-weight: 900; color: var(--p-green); }

        .savings-row {
            display: flex; justify-content: space-between;
            color: var(--p-offer);
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 10px;
            padding: 10px;
            background: #fff5f5;
            border-radius: 10px;
        }

        .btn-checkout-luxury {
            display: flex; align-items: center; justify-content: center; gap: 12px;
            background: var(--p-dark); color: #fff;
            padding: 20px; border-radius: 18px;
            text-decoration: none; font-weight: 800; font-size: 17px;
            transition: 0.3s;
        }
        .btn-checkout-luxury:hover { background: var(--p-green); transform: translateY(-3px); }

        .back-link { display: block; text-align: center; margin-top: 20px; color: #888; text-decoration: none; font-size: 14px; font-weight: 700; }
    </style>
</head>
<body>

<div class="main-wrapper">
    <div class="cart-grid <?= empty($items) ? 'is-empty' : '' ?>">
        
        <div class="cart-items-wrapper">
            <?php if(empty($items)): ?>
                <div style="text-align: center; padding: 60px 0;">
                    <i class="fa-solid fa-box-open" style="font-size: 60px; color: #eee; margin-bottom: 20px;"></i>
                    <h2>O seu carrinho está vazio</h2>
                    <a href="index.php" class="back-link">Voltar à loja</a>
                </div>
            <?php else: ?>
                <h1>O meu carrinho 🛒</h1>
                <?php foreach($items as $item): 
                    $is_promo = ($item['em_oferta'] == 1);
                    $preco_unitario = $item['price'] ?? $item['preco'];
                ?>
                    <div class="premium-card">
                        <img src="<?= htmlspecialchars($item['image'] ?? $item['imagem'] ?? '') ?>" class="prod-img" onerror="this.src='https://via.placeholder.com/150'">
                        
                        <div class="prod-details">
                            <h4>
                                <?= htmlspecialchars($item['name'] ?? $item['nome'] ?? 'Peça') ?>
                                <?php if($is_promo): ?><span class="badge-offer-mini">OFERTA</span><?php endif; ?>
                            </h4>
                            <span>CÓD: #<?= $item['id'] ?></span>
                        </div>

                        <div class="qty-control">
                            <a href="cart.php?update_id=<?= $item['cart_id'] ?>&new_qty=<?= $item['quantity'] - 1 ?>" class="qty-btn">-</a>
                            <span class="qty-num"><?= $item['quantity'] ?></span>
                            <a href="cart.php?update_id=<?= $item['cart_id'] ?>&new_qty=<?= $item['quantity'] + 1 ?>" class="qty-btn">+</a>
                        </div>

                        <div class="prod-price <?= $is_promo ? 'price-discounted' : '' ?>">
                            <?php if($is_promo && $item['preco_antigo'] > 0): ?>
                                <span class="old-price-cart"><?= number_format($item['preco_antigo'] * $item['quantity'], 2, ',', '.') ?>€</span>
                            <?php endif; ?>
                            <?= number_format($preco_unitario * $item['quantity'], 2, ',', '.') ?>€
                        </div>

                        <a href="remove_from_cart.php?id=<?= $item['cart_id'] ?>" class="remove-icon">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if(!empty($items)): ?>
        <div class="summary-sidebar">
            <h3>Resumo</h3>

            <div class="shipping-box-premium">
                <div class="shipping-info">
                    <span>Envio Grátis</span>
                    <span><?= ($total_carrinho >= $meta) ? 'CONCLUÍDO' : 'Faltam ' . number_format($falta, 2, ',', '.') . '€' ?></span>
                </div>
                <div class="progress-container">
                    <div class="progress-bar-fill" style="width: <?= $percentagem ?>%;"></div>
                </div>
            </div>

            <?php if($total_poupanca > 0): ?>
                <div class="savings-row">
                    <span><i class="fa fa-tag"></i> Descontos Aplicados</span>
                    <span>- <?= number_format($total_poupanca, 2, ',', '.') ?>€</span>
                </div>
            <?php endif; ?>

            <div class="total-row-premium">
                <span>Subtotal</span>
                <b><?= number_format($total_carrinho, 2, ',', '.') ?>€</b>
            </div>

            <a href="checkout.php" class="btn-checkout-luxury">
                FECHAR PEDIDO <i class="fa-solid fa-lock"></i>
            </a>

            <a href="index.php" class="back-link">Continuar a comprar</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>