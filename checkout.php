<?php
session_start();
require_once 'auth/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Cliente';
$mostrar_sucesso = false;

/* ========= 1. LÓGICA DE PROCESSAMENTO ========= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_checkout'])) {
    $stmt_del = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt_del->bind_param("i", $user_id);
    
    if ($stmt_del->execute()) {
        $conn->query("UPDATE users SET total_compras = total_compras + 1 WHERE id = '$user_id'");
        $mostrar_sucesso = true;
        $metodo_usado = $_POST['metodo'] ?? 'multibanco';
        $morada_final = $_POST['morada'] ?? '';
    }
}

require_once 'includes/header.php';

/* ========= 2. CARREGAR DADOS DO CARRINHO ========= */
if (!$mostrar_sucesso) {
    $total_carrinho = 0;
    $items = [];
    $sql = "SELECT p.*, c.quantity FROM cart c INNER JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()) {
        $preco = $row['price'] ?? $row['preco'] ?? 0;
        $total_carrinho += ($preco * $row['quantity']);
        $items[] = $row;
    }

    if (empty($items)) { header("Location: cart.php"); exit(); }
    $frete = ($total_carrinho >= 250) ? 0 : 9.90;
    $total_final = $total_carrinho + $frete;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Compra | Ecopeças</title>
    <link rel="icon" type="image/png" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background: #f9fafb; font-family: 'Inter', -apple-system, sans-serif; margin: 0; }
        
        .checkout-wrapper { 
            padding: 120px 20px 80px; 
            max-width: 1100px; 
            margin: auto; 
            min-height: 80vh;
        }

        .checkout-grid { display: grid; grid-template-columns: 1fr 400px; gap: 30px; }
        .checkout-card { background: #fff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .checkout-title { font-size: 22px; font-weight: bold; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; color: #333; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #666; font-size: 14px; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; outline: none; transition: 0.3s; box-sizing: border-box; }
        .form-group input:focus { border-color: #2e7d32; box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1); }

        .resumo-item { display: flex; justify-content: space-between; margin-bottom: 10px; color: #555; }
        .resumo-total { border-top: 2px solid #eee; margin-top: 15px; padding-top: 15px; display: flex; justify-content: space-between; font-size: 24px; font-weight: 800; color: #2e7d32; }
        
        .metodo-pagamento { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px; }
        .pagamento-opt { border: 2px solid #eee; padding: 15px; border-radius: 12px; cursor: pointer; text-align: center; transition: 0.3s; color: #666; }
        .pagamento-opt input { display: none; }
        .pagamento-opt:hover { border-color: #ccc; }
        .pagamento-opt.active { border-color: #2e7d32; background: #f0f9f1; color: #2e7d32; font-weight: bold; }

        .btn-pagar { width: 100%; background: #2e7d32; color: white; border: none; padding: 18px; border-radius: 15px; font-size: 18px; font-weight: bold; cursor: pointer; margin-top: 20px; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none;}
        .btn-pagar:hover { background: #246328; transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

        .success-box { text-align: center; padding: 50px; }
        .success-icon { font-size: 60px; color: #2e7d32; margin-bottom: 20px; }

        @media (max-width: 850px) { 
            .checkout-grid { grid-template-columns: 1fr; } 
            .checkout-wrapper { padding-top: 100px; }
        }
    </style>
</head>
<body>

<div class="checkout-wrapper">
    <?php if ($mostrar_sucesso): ?>
        <div class="checkout-card success-box">
            <div class="success-icon"><i class="fa fa-circle-check"></i></div>
            <h1>Encomenda Confirmada!</h1>
            <p style="font-size: 18px; color: #555;">
                Obrigado pela tua compra, <b><?= explode(' ', $user_name)[0] ?></b>!<br>
                O pagamento via <b><?= strtoupper($metodo_usado) ?></b> foi processado com sucesso.
            </p>
            <p style="color: #888;">As peças serão enviadas para: <?= htmlspecialchars($morada_final) ?></p>
            <a href="index.php" class="btn-pagar" style="width: auto; display: inline-flex; padding: 15px 40px; margin-top: 30px;">Voltar à Loja</a>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
        <script>
            confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 }, colors: ['#2e7d32', '#66d78b', '#ffffff'] });
        </script>

    <?php else: ?>
        <div class="checkout-grid">
            <form action="" method="POST" id="form-final">
                <input type="hidden" name="confirmar_checkout" value="1">
                <div class="checkout-card">
                    <div class="checkout-title"><i class="fa fa-truck"></i> Dados de Envio</div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Nome Completo</label>
                            <input type="text" name="nome" value="<?= htmlspecialchars($user_name) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Telemóvel</label>
                            <input type="text" name="telefone" placeholder="912 345 678" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Morada de Entrega</label>
                        <input type="text" name="morada" placeholder="Rua, nº da porta..." required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Código-Postal</label>
                            <input type="text" name="cp" placeholder="0000-000" required>
                        </div>
                        <div class="form-group">
                            <label>Cidade</label>
                            <input type="text" name="cidade" placeholder="Porto" required>
                        </div>
                    </div>

                    <div class="checkout-title" style="margin-top:30px;"><i class="fa fa-credit-card"></i> Pagamento</div>
                    <div class="metodo-pagamento">
                        <label class="pagamento-opt active" onclick="marcar(this)">
                            <input type="radio" name="metodo" value="multibanco" checked>
                            <i class="fa fa-university"></i><br>Multibanco
                        </label>
                        <label class="pagamento-opt" onclick="marcar(this)">
                            <input type="radio" name="metodo" value="mbway">
                            <i class="fa fa-mobile-screen-button"></i><br>MB Way
                        </label>
                    </div>
                </div>
            </form>

            <div class="checkout-sidebar">
                <div class="checkout-card">
                    <div class="checkout-title"><i class="fa fa-receipt"></i> Resumo</div>
                    <?php foreach($items as $item): ?>
                        <div class="resumo-item">
                            <span><?= $item['quantity'] ?>x <?= htmlspecialchars($item['name']) ?></span>
                            <span><?= number_format(($preco * $item['quantity']), 2, ',', '.') ?> €</span>
                        </div>
                    <?php endforeach; ?>

                    <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">
                        <div class="resumo-item"><span>Subtotal:</span><span><?= number_format($total_carrinho, 2, ',', '.') ?> €</span></div>
                        <div class="resumo-item"><span>Portes:</span><span><?= $frete == 0 ? '<b style="color:green">GRÁTIS</b>' : number_format($frete, 2, ',', '.') . ' €' ?></span></div>
                        <div class="resumo-total"><span>Total:</span><span><?= number_format($total_final, 2, ',', '.') ?> €</span></div>
                    </div>

                    <button type="submit" form="form-final" class="btn-pagar">Finalizar Compra <i class="fa fa-lock"></i></button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function marcar(el) {
        document.querySelectorAll('.pagamento-opt').forEach(opt => opt.classList.remove('active'));
        el.classList.add('active');
    }
</script>

</body>
</html>