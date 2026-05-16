<!DOCTYPE html>
<html>
<head>
    <title>Grocery System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<?php
$file = 'products.txt';
$products = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$id = $_GET['id'];
$new_products = [];

foreach($products as $line){
    $data = explode(",", $line);
    if($data[0] != $id){
        $new_products[] = $line;
    }
}

file_put_contents($file, implode("\n", $new_products)."\n");
header("Location: index.php");
?>
