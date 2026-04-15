<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/includes/header.php';

// 1. Capturar o ID do produto vindo da URL
$produto_id = $_GET['id'] ?? null;

// Aqui podes fazer uma query: SELECT * FROM produtos WHERE id = $produto_id
$produto = [
    'nome' => 'Peça Exemplo #' . $produto_id,
    'preco' => '45.00',
    'descricao' => 'Esta é uma peça de alta qualidade, verificada pela nossa equipa técnica para garantir total segurança no seu veículo.',
    'imagem' => 'https://blog.kroftools.com/wp-content/uploads/2023/11/pecas-de-automoveis.png',
    'stock' => 'Disponível'
];
?>

<main class="container" style="max-width: 1000px; margin: 40px auto; padding: 20px;">
    <div class="card" style="display: flex; gap: 30px; padding: 30px; align-items: flex-start;">
        
        <div style="flex: 1;">
            <img src="<?= $produto['imagem'] ?>" style="width: 100%; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
        </div>

        <div style="flex: 1; color: var(--texto);">
            <h1 style="color: var(--header-bg); margin-top: 0;"><?= $produto['nome'] ?></h1>
            <p style="font-size: 1.2rem; font-weight: bold; color: var(--preco);">Preço: <?= $produto['preco'] ?>€</p>
            <p style="line-height: 1.6;"><?= $produto['descricao'] ?></p>
            <p><strong>Estado:</strong> <?= $produto['stock'] ?></p>
            
            <hr style="border: 0; border-top: 1px solid var(--borda); margin: 20px 0;">
            
            <button style="background: #2e7d32; color: white; border: none; padding: 12px 25px; border-radius: 25px; font-weight: bold; cursor: pointer; width: 100%;">
                <i class="fa fa-shopping-cart"></i> Adicionar ao Carrinho
            </button>
            
            <a href="index.php" style="display: block; text-align: center; margin-top: 15px; color: var(--texto); text-decoration: none; font-size: 0.9rem;">
                ← Voltar à loja
            </a>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>