<?php
session_start();
require_once 'auth/config.php';

// Verificar se o utilizador está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Lógica de Tema
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
    $preco_venda = $row['price'] ?? 0;
    
    if (isset($row['em_oferta']) && $row['em_oferta'] == 1 && isset($row['preco_antigo']) && $row['preco_antigo'] > $preco_venda) {
        $poupanca_por_unidade = $row['preco_antigo'] - $preco_venda;
        $total_poupanca += ($poupanca_por_unidade * $row['quantity']);
    }
    
    $total_carrinho += ($preco_venda * $row['quantity']);
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
            --bg-body: #f3f4f6; 
            --p-red: #d32f2f; 
            --card-bg: #ffffff; 
            --text-main: #121212; 
            --border-color: #f0f0f0; 
        }
        body.dark { 
            --bg-body: #121212; 
            --card-bg: #1e1e1e; 
            --text-main: #ffffff; 
            --border-color: #333333; 
            --p-green-light: #143616; 
        }
        body { background-color: var(--bg-body); font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; color: var(--text-main); transition: background 0.3s ease; }
        
        .main-wrapper { padding: 160px 5% 80px; max-width: 1300px; margin: 0 auto; }
        
        /* Ajuste dinâmico da Grid */
        .cart-grid { display: grid; gap: 30px; align-items: start; }
        .grid-full { grid-template-columns: 1fr; }
        .grid-split { grid-template-columns: 1fr 400px; }
        
        @media (max-width: 992px) { .cart-grid { grid-template-columns: 1fr !important; } }

        .cart-items-wrapper { background: var(--card-bg); border-radius: 30px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .premium-card { display: flex; align-items: center; padding: 25px 0; border-bottom: 1px solid var(--border-color); position: relative; }
        
        .prod-img { width: 110px; height: 85px; border-radius: 18px; object-fit: cover; background: #eee; border: 1px solid var(--border-color); }

        .qty-control { display: flex; align-items: center; background: var(--bg-body); border-radius: 12px; padding: 4px; height: fit-content; }
        .qty-btn { width: 32px; height: 32px; background: var(--card-bg); border-radius: 10px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: var(--text-main); font-weight: 800; transition: 0.2s; }
        .qty-btn:hover { background: var(--p-green); color: white; }

        .summary-sidebar { background: var(--card-bg); padding: 35px; border-radius: 30px; position: sticky; top: 150px; border: 2px solid var(--p-green); box-shadow: 0 15px 40px rgba(0,0,0,0.15); }
        
        .progress-container { width: 100%; height: 12px; background: rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden; margin-top: 10px; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #4caf50, #2e7d32); transition: width 1s ease-in-out; }
        
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-weight: 600; }
        .total-row-premium { border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 20px; display: flex; justify-content: space-between; align-items: center; }
        .total-row-premium b { font-size: 34px; font-weight: 900; color: var(--p-green); }

        /* Animação da Caixa Vazia */
        @keyframes floatBox {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        .animated-box {
            display: inline-block;
            animation: floatBox 3s ease-in-out infinite;
            font-size: 100px !important;
            color: var(--p-green);
            margin-bottom: 25px;
        }

        .btn-back {
            display: inline-flex; align-items: center; gap: 10px; margin-top: 25px;
            padding: 12px 25px; background: transparent; color: var(--p-green);
            text-decoration: none; font-weight: 700; border: 2px solid var(--p-green);
            border-radius: 15px; transition: 0.3s;
        }
        .btn-back:hover { background: var(--p-green); color: white; transform: translateY(-3px); }
    </style>
</head>
<body class="<?= $theme === 'dark' ? 'dark' : '' ?>">

<div class="main-wrapper">
    <div class="cart-grid <?= empty($items) ? 'grid-full' : 'grid-split' ?>">
        
        <div class="cart-items-wrapper">
            <?php if(empty($items)): ?>
                <div style="text-align: center; padding: 40px 0;">
                    <i class="fa-solid fa-box-open animated-box"></i>
                    <h2 style="font-size: 28px; font-weight: 800; margin-bottom: 10px;">O seu carrinho está vazio</h2>
                    <p style="color: var(--text-sub); margin-bottom: 30px;">Parece que ainda não adicionou peças ecológicas ao seu pedido.</p>
                    <a href="index.php" style="background:var(--p-green); color:white; padding:18px 40px; border-radius:15px; text-decoration:none; font-weight:800; display: inline-block; transition: 0.3s; box-shadow: 0 10px 20px rgba(46,125,50,0.2);">Explorar Loja</a>
                </div>
            <?php else: ?>
                <h1 style="margin-top:0;">O meu carrinho 🛒</h1>
                <?php foreach($items as $item): 
                    $preco_unitario = $item['price'];
                    $preco_antigo = $item['preco_antigo'] ?? 0;
                    $is_oferta = ($item['em_oferta'] == 1 && $preco_antigo > $preco_unitario);
                    $foto_caminho = "uploads/perfil/produtos/" . $item['image_url'];
                ?>
                    <div class="premium-card">
                        <img src="<?= $foto_caminho ?>" class="prod-img" alt="<?= htmlspecialchars($item['name']) ?>" onerror="this.src='https://via.placeholder.com/110x80?text=Sem+Foto'">
                        <div class="prod-details" style="flex:1; padding: 0 20px;">
                            <div style="display:flex; align-items:center;">
                                <h4 style="margin:0;"><?= htmlspecialchars($item['name']) ?></h4>
                                <?php if($is_oferta): ?>
                                    <span style="background: var(--p-red); color: white; font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 5px; text-transform: uppercase; margin-left: 10px;">Oferta</span>
                                <?php endif; ?>
                            </div>
                            <span style="font-size:12px; opacity:0.6;">ID: #<?= $item['id'] ?></span>
                        </div>
                        <div class="qty-control">
                            <a href="cart.php?update_id=<?= $item['cart_id'] ?>&new_qty=<?= $item['quantity'] - 1 ?>" class="qty-btn">-</a>
                            <span class="qty-num" style="padding:0 15px; font-weight:bold;"><?= $item['quantity'] ?></span>
                            <a href="cart.php?update_id=<?= $item['cart_id'] ?>&new_qty=<?= $item['quantity'] + 1 ?>" class="qty-btn">+</a>
                        </div>
                        <div class="prod-price" style="margin-left:30px; min-width:100px; text-align:right;">
                            <?php if($is_oferta): ?>
                                <span style="text-decoration: line-through; color: #aaa; font-size: 0.85rem; display: block;"><?= number_format($preco_antigo * $item['quantity'], 2, ',', '.') ?>€</span>
                            <?php endif; ?>
                            <span style="font-weight:800; font-size:1.1rem; color: var(--text-main);"><?= number_format($preco_unitario * $item['quantity'], 2, ',', '.') ?>€</span>
                        </div>
                        <a href="remove_from_cart.php?id=<?= $item['cart_id'] ?>" style="margin-left:20px; color:var(--p-red);"><i class="fa-solid fa-trash"></i></a>
                    </div>
                <?php endforeach; ?>
                <a href="index.php" class="btn-back"><i class="fa-solid fa-cart-plus"></i> Escolher mais produtos</a>
            <?php endif; ?>
        </div>

        <?php if(!empty($items)): ?>
        <div class="summary-sidebar">
            <h3 style="margin-top:0;">Resumo do Pedido</h3>
            <div style="background: var(--p-green-light); padding: 15px; border-radius: 15px; margin-bottom: 25px;">
                <div style="display:flex; justify-content:space-between; font-size:13px; font-weight:700; color: var(--p-green);">
                    <span>Envio Grátis</span>
                    <span><?= ($total_carrinho >= $meta) ? 'CONCLUÍDO' : 'Faltam ' . number_format($falta, 2, ',', '.') . '€' ?></span>
                </div>
                <div class="progress-container"><div class="progress-fill" style="width: <?= $percentagem ?>%;"></div></div>
            </div>
            <div class="summary-row"><span>Subtotal</span><span><?= number_format($total_carrinho + $total_poupanca, 2, ',', '.') ?>€</span></div>
            <?php if($total_poupanca > 0): ?>
                <div class="summary-row" style="color:var(--p-red);"><span>Descontos</span><span>- <?= number_format($total_poupanca, 2, ',', '.') ?>€</span></div>
            <?php endif; ?>
            <div class="total-row-premium"><span>Total</span><b><?= number_format($total_carrinho, 2, ',', '.') ?>€</b></div>
            <a href="checkout.php" style="display:block; text-align:center; background:var(--p-green); color:white; padding:20px; border-radius:18px; text-decoration:none; font-weight:800; margin-top:25px; transition: 0.3s; box-shadow: 0 10px 20px rgba(46, 125, 50, 0.2);">FECHAR PEDIDO</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if (file_exists('includes/footer.php')) include 'includes/footer.php'; ?>
</body>
</html>