<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Ecopeças</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php
require_once __DIR__ . '/includes/header.php';
$products = [
  ["img" => "https://netun.com/cdn/shop/articles/01-Airbag_civicsi.jpg?v=1716802572", "name" => "Airbags\"", "desc" => "", "year" => 2020, "state" => "Usada", "price" => 50.00],
  ["img" => "https://blog.mixauto.com.br/wp-content/uploads/2018/05/caixa-de-cambio.jpg", "name" => "Motor e Transmição", "desc" => "Peça semi-nova de suspensão", "year" => 2019, "state" => "Semi-nova", "price" => 120.00],
  ["img" => "https://s7d9.scene7.com/is/image/dow/AdobeStock_385390317?qlt=82&ts=1692809401103&dpr=off", "name" => "Iluminação", "desc" => "Disco usado mas funcional", "year" => 2018, "state" => "Usada", "price" => 30.00]
];

foreach($products as $p){
    echo "<div class='card'>";
    echo "<img src='{$p['img']}' alt='{$p['name']}'>";
    echo "<h3>{$p['name']}</h3>";
    echo "<p>{$p['desc']}</p>";
    echo "<p><strong>Ano:</strong> {$p['year']} | <strong>Estado:</strong> {$p['state']}</p>";
    echo "<p class='price'>€".number_format($p['price'],2,',','.')."</p>";
    echo "<button class='btn'>Adicionar ao carrinho</button>";
    echo "</div>";
}

require_once __DIR__ . '/includes/footer.php';
?>
</body>
</html>