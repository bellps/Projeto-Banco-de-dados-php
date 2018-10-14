<?php
require 'init.php';
// pega o ID da URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
// valida o ID
if (empty($id)) {
    setcookie("error","ID para alteração não definido");
    header("Location: index.php");
    exit;
}
// busca os dados do usuário a ser editado
$PDO = db_connect();
$sql = "SELECT * FROM produtos WHERE id = :id";
$stmt = $PDO->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);
// se o método fetch() não retornar um array, significa que o ID não corresponde 
// a um usuário válido
if (!is_array($produto)) {
    echo "Nenhum produto encontrado";
    header("refresh:2;index.php");
    exit;
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="BS/css/bootstrap.min.css">
        <link rel="stylesheet" href="BS/css/bg.css">

        <title>Alteração de produto</title>
    </head>
    <body class="bg">
    <div class="container">
    <h1 class="display-4 text-center">Sistema de Cadastro</h1>
    <br>
    <div class="row">
    <div class="col-md-6">
        <h2>Alteração de produto</h2>
        <form action="edit.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome">Nome:</label><br>
            <input type="text" name="nome" id="nome" value="<?php echo $produto['nome'] ?>">
        </div>
        <div class="form-group">
            <label for="quantidade">Quantidade:</label><br>
            <input type="number" name="quantidade" id="quantidade" value="<?php echo $produto['quantidade'] ?>" required>
        </div>
        <div class="form-group">
            <label for="preco">Preço:</label><br>
            <input type="number" name="preco" id="preco" value="<?php echo $produto['preco'] ?>" required>
        </div>
        <div class="form-group">
            <label for="dataCadastro">Data de cadastro:</label><br>
            <input type="date" name="dataCadastro" id="dataCadastro" value="<?php echo $produto['dataCadastro']?>">
        </div>
        <div class="form-group">
            <label for="imagem">Imagem de referência:</label><br>
            <input type="file"  name="imagem" id="imagem" value="<?php echo $produto['imagem']?>">
        </div>
        <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="submit" value="Alterar" class="btn btn-outline-warning">
        </form>
        <?php 
                if(isset($_COOKIE["error"])){
                    echo '<div class="alert alert-danger" role="alert">'. $_COOKIE["error"] .'</div>';
                    setcookie('log', null, -1);
                }
                ?>
        </div>
        <div class="col-md-6 text-center">
        <img src="<?php echo $produto['imagem']?>" width="50%">
        <p><em><small>Imagem atual</small></em></p>
        </div>
        </div>
        </div>
    </body>
</html>