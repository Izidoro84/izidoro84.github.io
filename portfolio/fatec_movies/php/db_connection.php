<?php
$servername = "localhost";
$username = "root"; // Substitua pelo seu nome de usuário do banco de dados
$password = ""; // Substitua pela sua senha do banco de dados
$dbname = "fatec_movies"; // Substitua pelo nome do seu banco de dados

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>