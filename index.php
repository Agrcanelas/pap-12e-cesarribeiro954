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
  ["img" => "assets/images/wheel.jpg", "name" => "Roda 16\"", "desc" => "Roda usada em bom estado", "year" => 2020, "state" => "Usada", "price" => 50.00],
  ["img" => "assets/images/suspension.jpg", "name" => "Suspensão Dianteira", "desc" => "Peça semi-nova de suspensão", "year" => 2019, "state" => "Semi-nova", "price" => 120.00],
  ["img" => "assets/images/disc.jpg", "name" => "Disco de Travão", "desc" => "Disco usado mas funcional", "year" => 2018, "state" => "Usada", "price" => 30.00]
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