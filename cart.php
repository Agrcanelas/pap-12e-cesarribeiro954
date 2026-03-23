<?php
session_start();
require_once 'auth/config.php';

// Verificar se o utilizador está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Lógica de Tema (para evitar erros de variável indefinida)
$theme = $_SESSION['theme'] ?? 'light';

// Atualizar quantidade
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

// Importar Cabeçalho
require_once 'includes/header.php';

$user_id = $_SESSION['user_id'];
$total_carrinho = 0;
$total_poupanca = 0; 

// Procurar itens no carrinho
$sql = "SELECT p.*, c.quantity, c.id AS cart_id FROM cart c INNER JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$items = [];

while($row = $result->fetch_assoc()) {
    $preco_original = $row['price'] ?? $row['preco'] ?? 0;
    if (isset($row['em_oferta']) && $row['em_oferta'] == 1 && isset($row['preco_antigo']) && $row['preco_antigo'] > 0) {
        $poupanca_unidade = $row['preco_antigo'] - $preco_original;
        $total_poupanca += ($poupanca_unidade * $row['quantity']);
    }
    $total_carrinho += ($preco_original * $row['quantity']);
    $items[] = $row;
}

// Lógica da Barra de Progresso (Envio Grátis)
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
            --card-bg: #ffffff;
            --text-main: #121212;
            --text-sub: #666666;
            --border-color: #f0f0f0;
        }

        body.dark {
            --bg-body: #121212;
            --card-bg: #1e1e1e;
            --text-main: #ffffff;
            --text-sub: #bbbbbb;
            --border-color: #333333;
            --p-green-light: #143616;
        }

        body { 
            background-color: var(--bg-body);
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            color: var(--text-main);
            transition: background 0.3s ease;
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
            background: var(--card-bg);
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        /* ANIMAÇÃO DA CAIXA VAZIA */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .empty-cart-icon {
            font-size: 80px;
            color: var(--p-green);
            margin-bottom: 25px;
            animation: float 3s ease-in-out infinite;
            display: inline-block;
        }

        .cart-items-wrapper h1 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 35px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--text-main);
        }

        .premium-card {
            display: flex;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .qty-control {
            display: flex;
            align-items: center;
            background: var(--bg-body);
            border-radius: 12px;
            padding: 4px;
        }

        .qty-btn {
            width: 32px; height: 32px;
            background: var(--card-bg); border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; color: var(--text-main); font-weight: 800;
        }

        .summary-sidebar {
            background: var(--card-bg);
            padding: 35px;
            border-radius: 30px;
            position: sticky;
            top: 150px;
            border: 2px solid var(--p-green);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        /* --- EFEITO DA BARRA DE PROGRESSO --- */
        .progress-container { 
            width: 100%; 
            height: 12px; 
            background: rgba(0,0,0,0.1); 
            border-radius: 10px; 
            overflow: hidden; 
            margin-top: 10px;
        }
        
        .progress-fill {
            height: 100%; 
            background: linear-gradient(90deg, #4caf50, #2e7d32); 
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        /* Efeito de brilho a passar na barra */
        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shine 2s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .total-row-premium b { font-size: 34px; font-weight: 900; color: var(--p-green); }
    </style>
</head>
<body class="<?= $theme === 'dark' ? 'dark' : '' ?>">

<div class="main-wrapper">
    <div class="cart-grid <?= empty($items) ? 'is-empty' : '' ?>">
        
        <div class="cart-items-wrapper">
            <?php if(empty($items)): ?>
                <div style="text-align: center; padding: 60px 0;">
                    <div class="empty-cart-icon"><i class="fa-solid fa-box-open"></i></div>
                    <h2 class="empty-cart-text">O seu carrinho está vazio</h2>
                    <a href="index.php" class="btn-return" style="background:var(--p-green); color:white; padding:15px 30px; border-radius:12px; text-decoration:none; font-weight:800;">Explorar Loja</a>
                </div>
            <?php else: ?>
                <h1>O meu carrinho 🛒</h1>
                <?php foreach($items as $item): 
                    $is_promo = ($item['em_oferta'] == 1);
                    $preco_unitario = $item['price'] ?? $item['preco'];
                ?>
                    <div class="premium-card">
                        <img src="<?= htmlspecialchars($item['image'] ?? $item['imagem'] ?? '') ?>" class="prod-img" style="width:100px; border-radius:15px;">
                        <div class="prod-details" style="flex:1; padding: 0 20px;">
                            <h4><?= htmlspecialchars($item['name'] ?? $item['nome'] ?? 'Peça') ?></h4>
                            <span>CÓD: #<?= $item['id'] ?></span>
                        </div>
                        <div class="qty-control">
                            <a href="cart.php?update_id=<?= $item['cart_id'] ?>&new_qty=<?= $item['quantity'] - 1 ?>" class="qty-btn">-</a>
                            <span class="qty-num" style="padding:0 10px; font-weight:bold;"><?= $item['quantity'] ?></span>
                            <a href="cart.php?update_id=<?= $item['cart_id'] ?>&new_qty=<?= $item['quantity'] + 1 ?>" class="qty-btn">+</a>
                        </div>
                        <div class="prod-price" style="margin-left:20px; font-weight:800;">
                            <?= number_format($preco_unitario * $item['quantity'], 2, ',', '.') ?>€
                        </div>
                        <a href="remove_from_cart.php?id=<?= $item['cart_id'] ?>" style="margin-left:15px; color:red;"><i class="fa-solid fa-trash"></i></a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if(!empty($items)): ?>
        <div class="summary-sidebar">
            <h3 style="margin-top:0;">Resumo</h3>
            <div style="background: var(--p-green-light); padding: 15px; border-radius: 15px;">
                <div style="display:flex; justify-content:space-between; font-size:13px; font-weight:700;">
                    <span>Envio Grátis</span>
                    <span><?= ($total_carrinho >= $meta) ? 'CONCLUÍDO' : 'Faltam ' . number_format($falta, 2, ',', '.') . '€' ?></span>
                </div>
                <div class="progress-container">
                    <div class="progress-fill" style="width: <?= $percentagem ?>%;"></div>
                </div>
            </div>

            <div class="total-row-premium" style="display:flex; justify-content:space-between; align-items:baseline; margin: 20px 0;">
                <span>Subtotal</span>
                <b><?= number_format($total_carrinho, 2, ',', '.') ?>€</b>
            </div>

            <a href="checkout.php" style="display:block; text-align:center; background:var(--p-green); color:white; padding:20px; border-radius:18px; text-decoration:none; font-weight:800;">FECHAR PEDIDO</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php 
// Solução para o erro do Footer:
if (file_exists('includes/footer.php')) {
    include 'includes/footer.php';
} elseif (file_exists('footer.php')) {
    include 'footer.php';
} else {
    echo "";
}
?>
</body>
</html>