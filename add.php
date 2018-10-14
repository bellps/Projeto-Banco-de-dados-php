<?php
require_once 'init.php';

// pega os dados do formuário
$nome = isset($_POST['nome']) ? $_POST['nome'] : null;
$quantidade = isset($_POST['quantidade']) ? $_POST['quantidade'] : null;
$preco = isset($_POST['preco']) ? $_POST['preco'] : null;
$dataCadastro = date('d/m/Y');


//pegando a imagem
$image_file	= $_FILES["imagem"]["name"];
$type		= $_FILES["imagem"]["type"];
$size		= $_FILES["imagem"]["size"];
$temp		= $_FILES["imagem"]["tmp_name"];

// validação (bem simples, só pra evitar dados vazios)
if (empty($quantidade) || empty($preco) || empty($image_file)){
    setcookie("error","Preencha os campos obrigatórios.");
    header("Location: form-add.php");
}

//salvando o caminho da imagem
$path="upload/".$image_file;

//guardando a imagem numa pasta e conferindo se ela é ok
if($type=="image/jpg" || $type=='image/jpeg' || $type=='image/png' || $type=='image/gif') {	
	if(!file_exists($path)){
		if($size < 5000000){ 
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

// a data vem no formato dd/mm/YYYY, então precisamos converter para YYYY-mm-dd
$isoDate = dateConvert($dataCadastro);
echo $isoDate;

// insere no banco
$PDO = db_connect();
$sql = "INSERT INTO produtos(nome, quantidade, preco, dataCadastro, imagem) VALUES(:nome, :quantidade, :preco, :dataCadastro, :imagem)";
$stmt = $PDO->prepare($sql);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':quantidade', $quantidade);
$stmt->bindParam(':preco', $preco);
$stmt->bindParam(':dataCadastro', $isoDate);
$stmt->bindParam(':imagem', $path);
if ($stmt->execute())
{
    header('Location: index.php');
}
else
{
    setcookie("error","Erro ao cadastrar: ".print_r($stmt->errorInfo()).$errorMsg);
    header("Location: form-add.php");
}