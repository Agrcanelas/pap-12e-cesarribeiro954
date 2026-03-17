<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$lang = $_SESSION['lang'] ?? 'pt';

/* ========= LÓGICA DE VISUALIZAÇÃO (LOJA vs ADMIN) ========= */
$is_admin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');
$view_admin = ($is_admin && isset($_GET['view']) && $_GET['view'] === 'admin');

$t = [
    'pt' => [
        'slider1' => 'Peças ecológicas de qualidade', 'slider2' => 'Promoções especiais para si', 'slider3' => 'Sustentabilidade e confiança', 'slider4' => 'Melhor seleção de interiores!', 'slider5' => 'Fidelidade e confiança', 'see_products' => 'Ver Produtos', 'about_title' => 'Sobre a Ecopeças', 'about_text' => 'A Ecopeças é uma plataforma dedicada à venda de peças automóveis usadas e recondicionadas, focada na sustentabilidade e na economia circular.', 'about_slogan' => 'Ajude a salvar o planeta! ♻️', 'cat_motor' => 'Motor e Transmissão', 'cat_lighting' => 'Iluminação', 'cat_suspension' => 'Suspensão', 'cat_electric' => 'Elétrica', 'cat_interior' => 'Interior', 'footer_rights' => 'Todos os direitos reservados'
    ],
    'en' => [
        'slider1' => 'Quality eco-friendly parts', 'slider2' => 'Special offers for you', 'slider3' => 'Sustainability and trust', 'slider4' => 'Best interior selection!', 'slider5' => 'Loyalty and trust', 'see_products' => 'View Products', 'about_title' => 'About Ecopeças', 'about_text' => 'Ecopeças is a platform dedicated to selling used car parts.', 'about_slogan' => 'Help save the planet! ♻️', 'cat_motor' => 'Engine & Transmission', 'cat_lighting' => 'Lighting', 'cat_suspension' => 'Suspension', 'cat_electric' => 'Electrical', 'cat_interior' => 'Interior', 'footer_rights' => 'All rights reserved'
    ]
];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>Ecopeças</title>
    <link rel="icon" type="image/png" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        html { scroll-behavior: smooth; }
        body { background: url('https://wallpapers.com/images/hd/cool-green-background-yldkpcmn6kp9767o.jpg') fixed center/cover; margin: 0; display: flex; flex-direction: column; min-height: 100vh; font-family: sans-serif; }
        @keyframes subir { from { opacity: 0; transform: translateY(50px); } to { opacity: 1; transform: translateY(0); } }
        .animar-subida { animation: subir 1s ease-out forwards; }
        
        /* ADMIN STYLES */
        .admin-box { max-width:1100px; margin:40px auto; padding:40px; background:rgba(255,255,255,0.95); border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,0.2); }
        .admin-table { width:100%; border-collapse:collapse; margin-top:20px; background:#fff; border-radius:10px; overflow:hidden; }
        .admin-table th { background:#2e7d32; color:white; padding:15px; text-align:left; }
        .admin-table td { padding:15px; border-bottom:1px solid #ddd; vertical-align: middle; }
        
        /* LOJA STYLES */
        .slider-container { max-width:1200px; height:250px; margin:20px auto; overflow:hidden; border-radius:15px; box-shadow:0 0 20px rgba(102,215,139,0.6); }
        .slides-wrapper { display:flex; width:500%; transition:transform 1s ease-in-out; }
        .slider-slide { width:20%; position:relative; }
        .slider-slide img { width:100%; height:250px; object-fit:cover; }
        .slide-text { position:absolute; bottom:20px; left:20px; background:rgba(0,0,0,0.6); color:#fff; padding:12px; border-radius:12px; }
        .cards-container { display:flex; flex-wrap:wrap; justify-content:center; padding: 20px 0; }
        .card { width:250px; margin:15px; padding:15px; background:rgba(255,255,255,0.9); border-radius:15px; text-align:center; transition:0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .card:hover { transform:translateY(-8px); }
        .card img { width:100%; height:150px; object-fit:cover; border-radius:10px; }
        .card h3 { color: #2e7d32; font-weight: bold; margin: 15px 0; }
        .card .btn { display:inline-block; margin-top:10px; padding:10px 18px; background:#4caf70; color:#fff; border-radius:25px; text-decoration:none; font-weight:bold; }
        
        .about-box { max-width:1100px; margin:80px auto; padding:50px 60px; display:flex; align-items:center; justify-content:space-between; background: #ffffff; border-radius:40px; position: relative; box-shadow: 0 20px 50px rgba(0,0,0,0.15); border: 1px solid #ddd; }
        .folha-canto { position: absolute; font-size: 90px; color: #a5d6a7; z-index: 1; opacity: 0.8; pointer-events: none; }
        .folha-esq { top: -30px; left: -30px; }
        .folha-dir { bottom: -30px; right: -30px; transform: rotate(175deg); }
        .about-text { max-width:75%; position: relative; z-index: 2; }
        .about-text h2 { color:#1b5e20; font-size: 38px; margin-top: 0; font-weight: 800; }
        .about-text p { color: #111; line-height: 1.8; font-size: 18px; margin-bottom: 20px; }
        .about-logo img { max-width:180px; border-radius: 20px; }
        
        footer { background: #1b5e20; color: white; padding: 25px 0; text-align: center; margin-top: auto; }
        .footer-socials a { color: white; margin: 0 12px; font-size: 20px; text-decoration: none; }
    </style>
</head>
<body>
<?php require_once __DIR__ . '/includes/header.php'; ?>

<?php if ($view_admin): ?>
    <div class="admin-box animar-subida">
        <h2 style="color:#1b5e20; margin-bottom:20px;"><i class="fa fa-tools"></i> Gestão de Inventário</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = new mysqli("localhost", "root", "", "ecopecas");
                if ($conn->connect_error) {
                    echo "<tr><td colspan='5'>Erro de ligação à BD.</td></tr>";
                } else {
                    // Tenta detetar a tabela correta
                    $tabelas = ['produtos', 'products', 'pecas'];
                    $tabela_ativa = "";
                    foreach($tabelas as $t) {
                        $check = $conn->query("SHOW TABLES LIKE '$t'");
                        if($check->num_rows > 0) { $tabela_ativa = $t; break; }
                    }

                    if($tabela_ativa != "") {
                        $res = $conn->query("SELECT * FROM $tabela_ativa ORDER BY id DESC");
                        if ($res && $res->num_rows > 0) {
                            while($row = $res->fetch_assoc()): 
                                // Detetar colunas dinamicamente
                                $id = $row['id'] ?? $row['ID'];
                                $nome = $row['nome'] ?? $row['name'] ?? 'Sem Nome';
                                $preco = $row['preco'] ?? $row['price'] ?? 0;
                                $img = $row['imagem'] ?? $row['image'] ?? $row['foto'] ?? 'default.jpg';
                            ?>
                                <tr>
                                    <td>#<?= $id ?></td>
                                    <td><img src="uploads/produtos/<?= $img ?>" style="width:60px; height:60px; object-fit:cover; border-radius:8px; border: 1px solid #ddd;"></td>
                                    <td><strong><?= htmlspecialchars($nome) ?></strong></td>
                                    <td><?= number_format($preco, 2, ',', '.') ?>€</td>
                                    <td>
                                        <a href="editar_produto.php?id=<?= $id ?>" style="color:#1976d2; font-size:18px; margin-right:15px;"><i class="fa fa-edit"></i></a>
                                        <a href="eliminar_produto.php?id=<?= $id ?>" style="color:#d32f2f; font-size:18px;" onclick="return confirm('Apagar este produto?')"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile;
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center; padding:30px;'>A tabela '<strong>$tabela_ativa</strong>' está vazia no phpMyAdmin.</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center; padding:30px; color:red;'>Não encontrei as tabelas 'produtos', 'products' ou 'pecas'.</td></tr>";
                    }
                    $conn->close();
                } ?>
            </tbody>
        </table>
        <p style="margin-top:20px;"><a href="index.php" style="color:#2e7d32; font-weight:bold; text-decoration:none;">← Voltar à Loja</a></p>
    </div>

<?php else: ?>
    <div class="slider-container">
        <div class="slides-wrapper">
            <div class="slider-slide"><img src="https://blog.kroftools.com/wp-content/uploads/2023/11/pecas-de-automoveis.png"><div class="slide-text"><?= $t[$lang]['slider1'] ?></div></div>
            <div class="slider-slide"><img src="https://amr-auto.pt/wp-content/uploads/2024/01/reparacao_motor.jpg"><div class="slide-text"><?= $t[$lang]['slider2'] ?></div></div>
            <div class="slider-slide"><img src="https://inovegas.com.br/wp-content/uploads/2018/08/carro-miniatura-papel-na-grama.jpg"><div class="slide-text"><?= $t[$lang]['slider3'] ?></div></div>
            <div class="slider-slide"><img src="https://clickpetroleoegas.com.br/wp-content/uploads/2025/07/raw-8.jpg"><div class="slide-text"><?= $t[$lang]['slider4'] ?></div></div>
            <div class="slider-slide"><img src="https://img.freepik.com/fotos-gratis/acordo-de-negocios-aperto-de-mao-gesto-de-mao_53876-130006.jpg"><div class="slide-text"><?= $t[$lang]['slider5'] ?></div></div>
        </div>
    </div>

    <div class="cards-container animar-subida">
        <?php
        $cats = [
            ["img"=>"https://netun.com/cdn/shop/articles/01-Airbag_civicsi.jpg","name"=>"Airbags","file"=>"airbags.php"],
            ["img"=>"https://blog.mixauto.com.br/wp-content/uploads/2018/05/caixa-de-cambio.jpg","name"=>$t[$lang]['cat_motor'],"file"=>"MotorTransmição.php"],
            ["img"=>"https://s7d9.scene7.com/is/image/dow/AdobeStock_385390317","name"=>$t[$lang]['cat_lighting'],"file"=>"iluminação.php"],
            ["img"=>"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJ5NwDgRfalMhSg_JrDaskCoPjKOi3HHhxMA","name"=>$t[$lang]['cat_suspension'],"file"=>"suspencao.php"],
            ["img"=>"https://reparadorsa.com.br/wp-content/uploads/2022/12/RSA_MATERIAS-2_05-12_HEADER.png","name"=>$t[$lang]['cat_electric'],"file"=>"eletrica.php"],
            ["img"=>"https://global-img.bitauto.com/usercenter/yhzx/20250815/793/w1200_yichecar_522658379372509.jpg.webp","name"=>$t[$lang]['cat_interior'],"file"=>"interior.php"]
        ];
        foreach($cats as $c) {
            echo "<div class='card'><img src='{$c['img']}'><h3>{$c['name']}</h3><a class='btn' href='categorias/{$c['file']}'>{$t[$lang]['see_products']}</a></div>";
        }
        ?>
    </div>

    <div class="about-box">
        <i class="fa fa-leaf folha-canto folha-esq"></i>
        <i class="fa fa-leaf folha-canto folha-dir"></i>
        <div class="about-text">
            <h2><?= $t[$lang]['about_title'] ?></h2>
            <p><?= $t[$lang]['about_text'] ?></p>
            <h3 style="color:#2e7d32;"><?= $t[$lang]['about_slogan'] ?></h3>
        </div>
        <div class="about-logo"><img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg"></div>
    </div>
<?php endif; ?>

<footer>
    <div class="footer-socials">
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-whatsapp"></i></a>
    </div>
    <p>&copy; <?= date('Y') ?> Ecopeças — <?= $t[$lang]['footer_rights'] ?></p>
</footer>

<script>
let idx = 0; const wrp = document.querySelector('.slides-wrapper');
if(wrp){ setInterval(()=>{ idx = (idx+1)%5; wrp.style.transform = `translateX(-${idx*20}%)`; }, 5000); }
</script>
</body>
</html>