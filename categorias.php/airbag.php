<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Airbags - Ecopeças</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Favicon -->
<link rel="icon" href="https://img.freepik.com/vetores-premium/carro-ecologico-e-vetor-de-logotipo-de-icone-de-tecnologia-de-carro-verde-eletrico_661040-245.jpg?w=360" type="image/png">

<style>
/* Mesmos estilos das cards do index */
.cards-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 20px;
}

.card {
    width: 250px;
    padding: 15px;
    margin: 15px;
    display: inline-block;
    vertical-align: top;
    background: rgba(249, 255, 249, 0.8);
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    text-align: center;
}

.card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
}

.card h3 { font-size: 18px; margin: 10px 0; }
.card .btn {
    margin-top: 10px;
    padding: 10px 15px;
    border: none;
    background: #4caf70;
    color: #fff;
    border-radius: 50px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s;
}
.card .btn:hover { background: #66d78b; }
</style>
</head>
<body>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<h2 style="text-align:center; margin-top:30px; color:#2e7d32;">Categoria: Airbags</h2>

<div class="cards-container">
<?php
$airbags = [
  ["img" => "https://netun.com/cdn/shop/articles/01-Airbag_civicsi.jpg?v=1716802572", "name" => "Airbag Frontal"],
  ["img" => "https://example.com/airbag-lateral.jpg", "name" => "Airbag Lateral"],
  ["img" => "https://example.com/airbag-passageiro.jpg", "name" => "Airbag Passageiro"]
];

foreach($airbags as $a){
    echo "<div class='card'>";
    echo "<img src='{$a['img']}' alt='{$a['name']}'>";
    echo "<h3>{$a['name']}</h3>";
    echo "<button class='btn'>Ver Produto</button>";
    echo "</div>";
}
?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
