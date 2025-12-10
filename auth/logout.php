<?php
session_start();

// Limpa todas as variáveis de sessão
$_SESSION = [];

// Destroi a sessão
session_destroy();

// Redireciona para a página inicial
header("Location: ../index.php"); // ajuste o caminho se necessário
exit();
