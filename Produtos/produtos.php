<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
</head>
<body>
    <h2>Adicionar Produto</h2>

    <?php 
    // Verifique se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Conecte-se com o banco de dados
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
         $descricao = $_POST["descricao"];
         $preco = $_POST["preco"];
         $quantidade = $_POST["quantidade"];
 
        // Inserir os dados na tabela de produtos 
        $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade_estoque) VALUES ('$nome', '$descricao', $preco, $quantidade)";
        if ($conexao->query($sql) === TRUE) { 
            echo "Produto adicionado com sucesso!"; 
        } else {
            echo "Erro ao adicionar o produto: " . $conexao->error;
        }

        // Processar o upload da imagem
        $target_dir = "uploads/"; // Diretório onde as imagens serão armazenadas
        $target_file = $target_dir . basename($_FILES["imagem"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

         // Verifique se a imagem é válida
         if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["imagem"]["tmp_name"]);
            if ($check !== false) {
                echo "O arquivo é uma imagem - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "O arquivo não é uma imagem.";
                $uploadOk = 0;
            }
        }

        // Verifique se o arquivo já existe
        if (file_exists($target_file)) {
            echo "Desculpe, o arquivo já existe.";
            $uploadOk = 0;
        }

         // Verifique o tamanho do arquivo (opcional)
         if ($_FILES["imagem"]["size"] > 500000) {
            echo "Desculpe, o arquivo é muito grande.";
            $uploadOk = 0;
        }

         // Permitir apenas alguns formatos de imagem (você pode ajustar isso conforme necessário)
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Desculpe, o upload da imagem falhou.";
        } else {
            if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)) {
                echo "O arquivo " . htmlspecialchars(basename($_FILES["imagem"]["name"])) . " foi carregado com sucesso.";
        
            // Inserir os dados na tabela de produtos, incluindo o caminho da imagem
            $imagem = $target_file;
            $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade_estoque, imagem) VALUES ('$nome', '$descricao', $preco, $quantidade, '$imagem')";
            if ($conexao->query($sql) === TRUE) {
                echo "Produto adicionado com sucesso!";
            } else {
                echo "Erro ao adicionar o produto: " . $conexao->error;
            }
        } else {
                echo "Desculpe, ocorreu um erro ao fazer o upload do arquivo.";
            }
        }

        // Fechar a conexão com o banco de dados
        $conexao->close();
    }
    ?>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required></textarea>

        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" step="0.01" required>

        <label for="quantidade">Quantidade em Estoque:</label>
        <input type="number" id="quantidade" name="quantidade" required>

        <label for="imagem">Imagem do Produto:</label>
        <input type="file" id="imagem" name="imagem" accept="image/*" required>

        <button type="submit" name="submit">Adicionar Produto</button>
    </form>

</body>
</html>