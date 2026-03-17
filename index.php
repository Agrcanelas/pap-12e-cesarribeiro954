<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

/* ========= CONEXÃO ========= */
$conn = new mysqli("localhost", "root", "", "ecopecas");
if ($conn->connect_error) { die("Erro de ligação."); }

/* ========= IDIOMA ========= */
$lang = $_SESSION['lang'] ?? 'pt';
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
}

/* ========= CONFIGURAÇÕES DE ACESSO ========= */
$tabela = "products"; 
$is_admin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');
$view_admin = ($is_admin && isset($_GET['view']) && $_GET['view'] === 'admin');

/* ========= AÇÕES DE ADMIN (Recuperado) ========= */
if ($is_admin) {
    if (isset($_POST['save_edit'])) {
        $id = intval($_POST['prod_id']);
        $n = $conn->real_escape_string($_POST['novo_nome']);
        $p = floatval($_POST['novo_preco']);
        $conn->query("UPDATE $tabela SET name='$n', price=$p WHERE id=$id");
        header("Location: index.php?view=admin#prod-$id"); exit();
    }
    if (isset($_GET['delete_id'])) {
        $id = intval($_GET['delete_id']);
        $conn->query("UPDATE $tabela SET status='removido' WHERE id=$id");
        header("Location: index.php?view=admin#prod-$id"); exit();
    }
    if (isset($_GET['restore_id'])) {
        $id = intval($_GET['restore_id']);
        $conn->query("UPDATE $tabela SET status='ativo' WHERE id=$id");
        header("Location: index.php?view=admin#prod-$id"); exit();
    }
}

/* ========= DICIONÁRIO (CORRIGIDO) ========= */
$t = [
    'pt' => [
        'slider1'=>'Peças ecológicas de qualidade','slider2'=>'Promoções especiais','slider3'=>'Sustentabilidade','slider4'=>'Melhor seleção!','slider5'=>'Confiança Total',
        'about_title'=>'Sobre a Ecopeças',
        'about_text'=>'O site Ecopeças é um site de venda de peças automóveis usadas que prioriza o meio ambiente e a sustentabilidade. Aqui garantimos peças da melhor qualidade e devidamente testadas antes de serem vendidas.',
        'about_slogan'=>'Ajude a salvar o planeta! ♻️',
        'cat1'=>'Airbags','cat2'=>'Motor/Transmissão','cat3'=>'Iluminação','cat4'=>'Elétrica','cat5'=>'Interior','cat6'=>'Suspensão'
    ],
    'en' => [
        'slider1'=>'Eco-friendly quality parts','slider2'=>'Special deals','slider3'=>'Sustainability','slider4'=>'Best selection!','slider5'=>'Total Trust',
        'about_title'=>'About Ecopeças',
        'about_text'=>'The Ecopeças website is a marketplace for used auto parts that prioritizes the environment and sustainability. Here we guarantee parts of the highest quality, properly tested before being sold.',
        'about_slogan'=>'Help save the planet! ♻️',
        'cat1'=>'Airbags','cat2'=>'Engine/Transmission','cat3'=>'Lighting','cat4'=>'Electric','cat5'=>'Interior','cat6'=>'Suspension'
    ]
];
$txt = $t[$lang];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>Ecopeças - Oficial</title>
    
    <link rel="icon" type="image/png" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg"> 
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --bg-body: url('https://wallpapers.com/images/hd/cool-green-background-yldkpcmn6kp9767o.jpg');
            --bg-card: rgba(255, 255, 255, 0.96);
            --text-main: #333;
            --primary-green: #2e7d32;
            --accent-green: #4caf50;
        }
        body.dark-mode {
            --bg-body: #121212;
            --bg-card: #1e1e1e;
            --text-main: #e0e0e0;
            --primary-green: #4caf50;
        }

        body { background: var(--bg-body) fixed center/cover; margin: 0; font-family: sans-serif; transition: 0.3s; color: var(--text-main); display: flex; flex-direction: column; min-height: 100vh; overflow-x: hidden; }

        /* BADGE ADMIN PROFISSIONAL */
        .badge-admin {
            background: linear-gradient(135deg, #1b5e20, #4caf50);
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* ADMIN TABLE STYLES */
        .admin-box { max-width:1100px; margin:40px auto; padding:35px; background:var(--bg-card); border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,0.3); }
        .admin-table { width:100%; border-collapse:collapse; background:white; color: #333; border-radius:10px; overflow:hidden; }
        .admin-table th { background:#2e7d32; color:white; padding:15px; text-align:left; }
        .admin-table td { padding:12px; border-bottom:1px solid #ddd; }
        .item-removido { background-color: #ffebee !important; opacity: 0.7; }

        /* SLIDER */
        .slider-container { max-width:1100px; height:280px; margin:20px auto; overflow:hidden; border-radius:15px; box-shadow:0 8px 20px rgba(0,0,0,0.4); }
        .slides-wrapper { display:flex; width:500%; transition:1s ease; }
        .slider-slide { width:20%; position:relative; }
        .slider-slide img { width:100%; height:280px; object-fit:cover; }
        .slide-text { position:absolute; bottom:20px; left:20px; background:rgba(0,0,0,0.7); color:white; padding:10px 20px; border-radius:8px; }

        /* CATEGORIAS */
        .cards-container { display:flex; flex-wrap:wrap; justify-content:center; padding:20px; }
        .card { width:240px; margin:15px; padding:15px; background:var(--bg-card); border-radius:15px; text-align:center; box-shadow:0 4px 15px rgba(0,0,0,0.2); transition: 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .card img { width:100%; height:140px; object-fit:cover; border-radius:10px; }
        .card h3 { color: var(--primary-green); margin:15px 0; font-size: 20px; font-weight: bold; }
        .btn-cat { display:inline-block; padding:10px 25px; background:var(--accent-green); color:white; border-radius:25px; text-decoration:none; font-weight:bold; }

        /* SOBRE NÓS */
        .about-box { max-width:1000px; margin:80px auto; padding:60px; background:var(--bg-card); border-radius:40px; display:flex; align-items:center; position:relative; box-shadow:0 15px 40px rgba(0,0,0,0.2); }
        .folha { position:absolute; font-size:140px; color:var(--primary-green); opacity:1.0; pointer-events: none; z-index: 1; }
        .f-top { top: -60px; left: -60px; transform: rotate(-15deg); }
        .f-bottom { bottom: -60px; right: -60px; transform: rotate(165deg); }
        .about-content { position: relative; z-index: 2; flex: 1; }

        /* FOOTER */
        .ultimate-footer { background: linear-gradient(135deg, #0a1f0c 0%, #050d06 100%); color: #ffffff; padding: 60px 20px 30px; margin-top: 80px; box-shadow: 0 -10px 40px rgba(0,0,0,0.5); }
        .footer-grid { max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 40px; }
        .footer-head { font-size: 18px; font-weight: bold; margin-bottom: 20px; color: var(--accent-green); text-transform: uppercase; letter-spacing: 1px; }
        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 12px; }
        .footer-links a { color: #ccc; text-decoration: none; transition: 0.3s; display: flex; align-items: center; }
        .footer-links a:hover { color: #fff; transform: translateX(5px); }
        .footer-links i { margin-right: 10px; font-size: 12px; color: var(--accent-green); }
        .social-box { display: flex; gap: 15px; margin-top: 20px; }
        .social-box a { width: 40px; height: 40px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; border-radius: 50%; color: #fff; transition: 0.3s; }
        .social-box a:hover { background: var(--accent-green); transform: translateY(-3px); }
        .footer-bottom { text-align: center; margin-top: 50px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 14px; color: #777; }
    </style>
</head>
<body class="<?= (isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark') ? 'dark-mode' : '' ?>">

<?php require_once __DIR__ . '/includes/header.php'; ?>

<?php if ($is_admin): ?>
<div style="max-width: 1100px; margin: 20px auto; padding: 0 20px; text-align: right;">
    <div class="badge-admin">
        <i class="fas fa-user-shield"></i> Sessão: Administrador
    </div>
</div>
<?php endif; ?>

<?php if ($view_admin): ?>
    <div class="admin-box">
        <h2 style="color:var(--primary-green); margin-bottom: 25px;"><i class="fa fa-tools"></i> Painel de Controlo de Stock</h2>
        <table class="admin-table">
            <thead>
                <tr><th>#</th><th>Imagem</th><th>Nome do Produto</th><th>Preço</th><th>Ações</th></tr>
            </thead>
            <tbody>
                <?php
                $res = $conn->query("SELECT * FROM $tabela ORDER BY id ASC");
                $pos = 1;
                while($row = $res->fetch_assoc()):
                    $id = $row['id'];
                    $st = $row['status'] ?? 'ativo';
                    $is_editing = (isset($_GET['edit_id']) && $_GET['edit_id'] == $id);
                ?>
                <tr id="prod-<?= $id ?>" class="<?= ($st == 'removido') ? 'item-removido' : '' ?>">
                    <form method="POST" action="index.php?view=admin#prod-<?= $id ?>">
                        <td><strong><?= $pos++ ?></strong><input type="hidden" name="prod_id" value="<?= $id ?>"></td>
                        <td><img src="uploads/produtos/<?= $row['image_url'] ?>" width="45" height="45" style="object-fit:cover; border-radius:5px;" onerror="this.src='https://via.placeholder.com/50'"></td>
                        <td><?php if($is_editing): ?><input type="text" name="novo_nome" value="<?= htmlspecialchars($row['name']) ?>" style="width:90%; padding:5px;"><?php else: ?><?= htmlspecialchars($row['name']) ?><?php endif; ?></td>
                        <td><?php if($is_editing): ?><input type="number" step="0.01" name="novo_preco" value="<?= $row['price'] ?>" style="width:70px;"><?php else: ?><?= number_format($row['price'],2,',','.') ?>€<?php endif; ?></td>
                        <td>
                            <?php if($is_editing): ?>
                                <button type="submit" name="save_edit" style="color:green; border:none; background:none; cursor:pointer;"><i class="fa fa-check-circle fa-lg"></i></button>
                                <a href="index.php?view=admin#prod-<?= $id ?>" style="color:red; margin-left:10px;"><i class="fa fa-times-circle fa-lg"></i></a>
                            <?php else: ?>
                                <a href="index.php?view=admin&edit_id=<?= $id ?>#prod-<?= $id ?>" style="color:blue; margin-right:15px;"><i class="fa fa-edit"></i></a>
                                <?php if($st == 'removido'): ?>
                                    <a href="index.php?view=admin&restore_id=<?= $id ?>#prod-<?= $id ?>" style="color:green; font-weight:bold; font-size:11px;">RESTAURAR</a>
                                <?php else: ?>
                                    <a href="index.php?view=admin&delete_id=<?= $id ?>#prod-<?= $id ?>" style="color:red;"><i class="fa fa-trash"></i></a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p style="margin-top:20px;"><a href="index.php" style="color:var(--primary-green); font-weight:bold; text-decoration:none;">← Voltar para a Loja</a></p>
    </div>

<?php else: ?>
    <div class="slider-container"><div class="slides-wrapper" id="sw">
        <?php for($i=1;$i<=5;$i++): 
            $img = ["","https://blog.kroftools.com/wp-content/uploads/2023/11/pecas-de-automoveis.png","https://amr-auto.pt/wp-content/uploads/2024/01/reparacao_motor.jpg","https://inovegas.com.br/wp-content/uploads/2018/08/carro-miniatura-papel-na-grama.jpg","https://clickpetroleoegas.com.br/wp-content/uploads/2025/07/raw-8.jpg","https://img.freepik.com/fotos-gratis/acordo-de-negocios-aperto-de-mao-gesto-de-mao_53876-130006.jpg"];
        ?>
        <div class="slider-slide"><img src="<?= $img[$i] ?>"><div class="slide-text"><?= $txt['slider'.$i] ?></div></div>
        <?php endfor; ?>
    </div></div>

    <div class="cards-container">
        <?php
        $cats = [
            ["img"=>"https://netun.com/cdn/shop/articles/01-Airbag_civicsi.jpg","name"=>$txt['cat1'],"file"=>"airbags.php"],
            ["img"=>"https://blog.mixauto.com.br/wp-content/uploads/2018/05/caixa-de-cambio.jpg","name"=>$txt['cat2'],"file"=>"MotorTransmição.php"],
            ["img"=>"https://s7d9.scene7.com/is/image/dow/AdobeStock_385390317","name"=>$txt['cat3'],"file"=>"iluminação.php"],
            ["img"=>"https://reparadorsa.com.br/wp-content/uploads/2022/12/RSA_MATERIAS-2_05-12_HEADER.png","name"=>$txt['cat4'],"file"=>"eletrica.php"],
            ["img"=>"https://global-img.bitauto.com/usercenter/yhzx/20250815/793/w1200_yichecar_522658379372509.jpg.webp","name"=>$txt['cat5'],"file"=>"interior.php"],
            ["img"=>"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJ5NwDgRfalMhSg_JrDaskCoPjKOi3HHhxMA","name"=>$txt['cat6'],"file"=>"suspencao.php"]
        ];
        foreach($cats as $c) {
            echo "<div class='card'><img src='{$c['img']}'><h3>{$c['name']}</h3><a class='btn-cat' href='categorias/{$c['file']}'>Ver Peças</a></div>";
        }
        ?>
    </div>

    <div class="about-box">
        <i class="fa fa-leaf folha f-top"></i>
        <i class="fa fa-leaf folha f-bottom"></i>
        <div class="about-content">
            <h2 style="color:#1b5e20; font-size:35px; margin-top:0; font-weight:bold;"><?= $txt['about_title'] ?></h2>
            <p style="font-size:20px; line-height:1.7; color: #333; margin: 20px 0;">
                <?= $txt['about_text'] ?>
            </p>
            <h3 style="color:#2e7d32; font-size:24px; font-weight: bold;"><?= $txt['about_slogan'] ?></h3>
        </div>
        <div style="margin-left:40px; z-index: 2;">
            <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg" width="180" style="border-radius:30px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        </div>
    </div>
<?php endif; ?>

<footer class="ultimate-footer">
    <div class="footer-grid">
        <div>
            <h4 class="footer-head">Ecopeças</h4>
            <p style="color:#bbb; line-height:1.6;">Especialistas na venda de peças usadas com foco no ambiente. Garantimos qualidade e sustentabilidade.</p>
            <div class="social-box">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        <div>
            <h4 class="footer-head">Categorias</h4>
            <ul class="footer-links">
                <li><a href="categorias/airbags.php"><i class="fa fa-caret-right"></i> Airbags</a></li>
                <li><a href="categorias/MotorTransmição.php"><i class="fa fa-caret-right"></i> Motores / Transmissão</a></li>
                <li><a href="categorias/iluminação.php"><i class="fa fa-caret-right"></i> Iluminação</a></li>
                <li><a href="categorias/eletrica.php"><i class="fa fa-caret-right"></i> Elétrica</a></li>
                <li><a href="categorias/interior.php"><i class="fa fa-caret-right"></i> Interior</a></li>
                <li><a href="categorias/suspencao.php"><i class="fa fa-caret-right"></i> Suspensão</a></li>
            </ul>
        </div>
        <div>
            <h4 class="footer-head">Contacto</h4>
            <ul class="footer-links" style="color:#bbb">
                <li><i class="fa fa-phone"></i> +351 900 000 000</li>
                <li><i class="fa fa-envelope"></i> suporte@ecopecas.pt</li>
                <li><i class="fa fa-map-marker-alt"></i> Porto, Portugal</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> <strong>Ecopeças</strong>. Todos os direitos reservados. ♻️</p>
    </div>
</footer>

<script>
    let idx = 0; const wrp = document.getElementById('sw');
    if(wrp){ setInterval(()=>{ idx = (idx+1)%5; wrp.style.transform = `translateX(-${idx*20}%)`; }, 5000); }
</script>
</body>
</html>