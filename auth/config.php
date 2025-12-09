<?php
$host = "localhost";
$db = "ecopecas"; 
$user = "root"; // ou outro usuário
$pass = "";     // sua senha do MySQL

$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error){
    die("Conexão falhou: " . $conn->connect_error);
}
?>
