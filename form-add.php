<?php
require 'init.php';
?>
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="BS/css/bootstrap.min.css">
        <link rel="stylesheet" href="BS/css/bg.css">
        <meta charset="utf-8">
        <title>Cadastro de Produto</title>
    </head>
    <body class="bg">
    <div class="container">
        <h1 class="display-4 text-center">Sistema de Cadastro</h1>
        <h2>Cadastro de Produto</h2>

        <form action="add.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome">Nome:</label><br>
            <input type="text" name="nome" id="nome">
        </div>
        <div class="form-group">
            <label for="quantidade">Quantidade:</label><br>
            <input type="number" name="quantidade" id="quantidade" required>
        </div>
        <div class="form-group">
            <label for="preco">Preço:</label><br>
            <input type="number" name="preco" id="preco" required>
        </div>
        <div class="form-group">
            <label for="imagem">Imagem de referência:</label><br>
            <input type="file" name="imagem" id="imagem" required>
        </div>
        <input type="submit" class="btn btn-outline-info" value="Cadastrar">
        </form>
        <?php 
                if(isset($_COOKIE["error"])){
                    echo '<div class="alert alert-danger" role="alert">'. $_COOKIE["error"] .'</div>';
                    setcookie('log', null, -1);
                }
                ?>
    </div>
    </body>
</html>