<?php
$host = 'localhost';       // normal: localhost
$user = 'root';            // seu usuário do MySQL
$pass = '';                // sua senha do MySQL
$dbname = 'ecopecas';   // nome da base de dados

// Criar conexão
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Definir charset
$conn->set_charset("utf8");
?>
