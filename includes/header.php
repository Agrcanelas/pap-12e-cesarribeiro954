<header style="background:#2e7d32; padding:15px 30px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 4px 10px rgba(0,0,0,0.1); border-radius:10px;">

    <?php  
    // Página atual
    $pagina = basename($_SERVER['PHP_SELF']);
    ?>

    <!-- LOGO + NOME -->
    <div class="logo-container" style="display:flex; align-items:center; gap:10px;">
        <img src="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg"
             alt="Logo Ecopeças"
             style="height:50px; width:auto; object-fit:contain;">

        <div class="logo-text" style="font-size:28px; font-weight:bold; color:#fff;">
            <?php echo "Ecopeças"; ?>
        </div>
    </div>

    <!-- MENU -->
    <nav class="menu" style="display:flex; gap:25px; font-weight:bold;">

        <a href="./index.php"
           style="color:#fff; text-decoration:none; <?php echo ($pagina=='index.php'?'text-decoration:underline;':''); ?>">
            <i class="fa fa-home"></i> Home
        </a>

        <a href="./cart.php"
           style="color:#fff; text-decoration:none; <?php echo ($pagina=='cart.php'?'text-decoration:underline;':''); ?>">
            <i class="fa fa-shopping-cart"></i> Carrinho
        </a>

    </nav>

    <!-- LOGIN -->
    <div class="user-menu">
        <a href="auth/login.php"
           style="color:#fff; font-weight:bold; text-decoration:none; <?php echo ($pagina=='login.php'?'text-decoration:underline;':''); ?>">
            <i class="fa fa-user"></i> Login
        </a>
    </div>

</header>

<main>

<!-- =========================== -->
<!-- ADICIONAR IMAGEM DE FUNDO -->
<style>
body {
    /* Substituir pelo endereço da tua imagem */
    background: url('https://png.pngtree.com/thumb_back/fh260/background/20210824/pngtree-yellow-green-background-stock-images-wallpaper-image_769660.jpg') no-repeat center center/cover;
}
</style>
<!-- =========================== -->

