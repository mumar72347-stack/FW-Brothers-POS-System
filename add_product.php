<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

$file = "products.txt";

if(isset($_POST['submit'])){

    $id = time();
    $barcode = trim($_POST['barcode']);
    $name = trim($_POST['name']);
    $original = $_POST['original'];
    $retail = $_POST['retail'];
    $stock = $_POST['stock'];

    // SAVE FORMAT (OLD SYSTEM COMPATIBLE)
    $line = "$id,$barcode,$name,$original,$retail,$stock\n";

    file_put_contents($file, $line, FILE_APPEND);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f4f6f9;
            font-family:Arial;
        }

        .box{
            max-width:450px;
            margin:auto;
            margin-top:50px;
            background:white;
            padding:25px;
            border-radius:15px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>

<div class="box">

<h3 class="mb-3">Add Product</h3>

<form method="post">

   

    <input type="text"
           name="name"
           class="form-control mb-2"
           placeholder="Product Name"
           required>

    <input type="number"
           name="original"
           class="form-control mb-2"
           placeholder="Original Price"
           required>

    <input type="number"
           name="retail"
           class="form-control mb-2"
           placeholder="Retail Price"
           required>

    <input type="number"
           name="stock"
           class="form-control mb-3"
           placeholder="Stock Quantity"
           required>

    <button name="submit" class="btn btn-success w-100">
        Save Product
    </button>

</form>

</div>

</body>
</html>