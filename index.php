<?php
require_once 'init.php';
// abre a conexão
$PDO = db_connect();
// SQL para contar o total de registros
// A biblioteca PDO possui o método rowCount(), 
// mas ele pode ser impreciso.
// É recomendável usar a função COUNT da SQL
$sql_count = "SELECT COUNT(*) AS total FROM produtos";
// conta o total de registros
$stmt_count = $PDO->prepare($sql_count);
$stmt_count->execute();
// SQL para selecionar os registros
$sql = "SELECT * FROM produtos ORDER BY nome ASC";
$total = $stmt_count->fetchColumn();
// seleciona os registros
$stmt = $PDO->prepare($sql);
$stmt->execute();
?>
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="BS/css/bootstrap.min.css">
        <link rel="stylesheet" href="BS/css/bg.css">
        <meta charset="utf-8">
        <title>Produtos</title>
    </head>
    <body class="bg">
    <div class="container mx-auto">
        <h1 class="display-4 text-center">Cadastro de produtos</h1>
        <p class="text-right"><a href="form-add.php" class="btn btn-outline-info">Adicionar Produto</a></p>
        <h2>Lista de Produtos</h2>
        <p class="lead">Total de produtos: <?php echo $total ?></p>
        <?php 
                if(isset($_COOKIE["error"])){
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'. $_COOKIE["error"] .'</div>';
                    setcookie('error', null, -1);
                    header("refresh:2;index.php");
                }
                ?>
        <?php if ($total > 0){ ?>
            <table class="table table-striped text-center">
                <thead>
                    <tr class="bg-info text-light">
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Quantidade</th>
                        <th>Preço</th>
                        <th>Data de cadastro</th>
                        <th>Imagem</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($produto=$stmt->fetch(PDO::FETCH_ASSOC)){ ?>
                        <tr>
                            <td><?php echo $produto['id'] ?></td>
                            <td><?php echo $produto['nome'] ?></td>
                            <td><?php echo $produto['quantidade'] ?></td>
                            <td><?php echo $produto['preco'] ?></td>
                            <td><?php echo dateConvert($produto['dataCadastro'])?></td>
                            <td><img src="<?php echo $produto['imagem']; ?>" width="100px"></td>
                            <td><a href="form-edit.php?id=<?php echo $produto['id'] ?>" class="btn btn-warning">Editar</a>
                                <a href="delete.php?id=<?php echo $produto['id'] ?>" class="btn btn-danger"
                                onclick="return confirm('Tem certeza de que deseja remover?');">
                                    Remover
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php }else{ ?>
            <p><em>Nenhum usuário registrado</em></p>
        <?php } ?>
        </div>
    </body>
</html>