<?php
require_once 'init.php';
// pega o ID da URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
// valida o ID
if (empty($id))
{
    echo "ID não informado";
    exit;
}
// remove do banco
$PDO = db_connect();

$sql = "SELECT imagem FROM produtos WHERE id = :id";
$stmt = $PDO->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
unlink($row['imagem']);


$sql = "DELETE FROM produtos WHERE id = :id";
$stmt = $PDO->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);


if ($stmt->execute())
{
    header('Location: index.php');
}
else
{
    echo "Erro ao remover";
    print_r($stmt->errorInfo());
}