<?php
require_once 'init.php';
// resgata os valores do formulário
$nome = isset($_POST['nome']) ? $_POST['nome'] : null;
$quantidade = isset($_POST['quantidade']) ? $_POST['quantidade'] : null;
$preco = isset($_POST['preco']) ? $_POST['preco'] : null;
$dataCadastro = isset($_POST['dataCadastro']) ? $_POST['dataCadastro'] : null;
$id = isset($_POST['id']) ? $_POST['id'] : null;

//pegando a imagem
$image_file	= $_FILES["imagem"]["name"];
$type		= $_FILES["imagem"]["type"];
$size		= $_FILES["imagem"]["size"];
$temp		= $_FILES["imagem"]["tmp_name"];

// validação (bem simples, só pra evitar dados vazios)
if (empty($quantidade) || empty($preco)){
    setcookie("error","Preencha os campos obrigatórios.");
    header("Location: form-edit.php?id=$id");
}

//salvando o caminho da imagem
$path="upload/".$image_file;
$directory="upload/"; //set upload folder path for update time previous file remove and new file upload for next use

//pegando a imagem antiga para se caso o usuário não altera-la
$PDO = db_connect();
$sql = "SELECT * FROM produtos WHERE id =:id"; 
$stmt = $PDO->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

//guardando a imagem numa pasta e conferindo se ela é ok
if(!empty($image_file)){
    if($type=="image/jpg" || $type=='image/jpeg' || $type=='image/png' || $type=='image/gif') {	
        if(!file_exists($path)){
            if($size < 5000000){ 
                unlink($row['imagem']); //exclui a imagem antiga
                move_uploaded_file($temp, "upload/" .$image_file); 
            }else{
                $errorMsg="Seu arquivo é muito grande.";
            }
        }else{	
            $errorMsg="O arquivo já existe.";
        }  
    }else{
        $errorMsg="Extensão não reconhecida.";
    }
}else{
    $path=$row['imagem']; //se não secionar nenhuma imagem vai a antiga
}

// atualiza o banco
//$sql = "UPDATE produtos SET nome = :nome, quantidade = :quantidade, preco = :preco, dataCadastro = :dataCadastro, imagem = :imagem WHERE id = :id";
$stmt_up = $PDO->prepare("UPDATE produtos SET nome = :nome, quantidade = :quantidade, preco = :preco, dataCadastro = :dataCadastro, imagem = :imagem WHERE id = :id");
$stmt_up->bindParam(':nome', $nome);
$stmt_up->bindParam(':quantidade', $quantidade);
$stmt_up->bindParam(':preco', $preco);
$stmt_up->bindParam(':dataCadastro', $dataCadastro);
$stmt_up->bindParam(':imagem', $path);
$stmt_up->bindParam(':id', $id);
if ($stmt_up->execute())
{
    header('Location: index.php');
}
else
{
    setcookie("error","Erro ao alterar: ".print_r($stmt->errorInfo()).$errorMsg);
    header("Location: form-add.php");
}