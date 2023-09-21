<?php
// Conecte-se ao banco de dados (substitua com suas próprias configurações)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cadastro";

$conexao = new mysqli($servername, $username, $password, $dbname);

// Verifique a conexão com o banco de dados
if ($conexao->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
}

// Recupere os dados do formulário
$nome = $_POST["nome"];
$endereco = $_POST["endereco"];
$email = $_POST["email"];
$senha = password_hash($_POST["senha"], PASSWORD_DEFAULT); // Criptografe a senha

// Inserir o novo usuário na tabela de usuários
$sql = "INSERT INTO usuarios (nome, endereco, email, senha) VALUES ('$nome', '$endereco', '$email', '$senha')";

if ($conexao->query($sql) === TRUE) {
    echo "Registro de usuário realizado com sucesso!";
} else {
    echo "Erro no registro de usuário: " . $conexao->error;
}

// Fechar a conexão com o banco de dados
$conexao->close();
?>