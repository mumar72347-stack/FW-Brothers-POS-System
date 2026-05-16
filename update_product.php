<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

$file = "products.txt";
$products = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$id = $_GET['id'] ?? null;
$selected = [];

/* FIND PRODUCT */
foreach($products as $line){
    $data = explode(",", $line);
    if($data[0] == $id){
        $selected = $data;
        break;
    }
}

/* IF NOT FOUND */
if(empty($selected)){
    echo "Product not found";
    exit;
}

/* UPDATE PRODUCT */
if(isset($_POST['submit'])){

    $new_products = [];

    foreach($products as $line){

        $data = explode(",", $line);

        if($data[0] == $id){

            $data[1] = $_POST['barcode'];
            $data[2] = $_POST['name'];
            $data[3] = $_POST['original'];
            $data[4] = $_POST['retail'];
            $data[5] = $_POST['stock'];
        }

        $new_products[] = implode(",", $data);
    }

    file_put_contents($file, implode("\n", $new_products)."\n");

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Product</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<div class="container">

<h2>Update Product</h2>

<form method="post">

    
    

    <div class="mb-2">
        <label>Name</label>
        <input type="text" name="name" class="form-control"
               value="<?= $selected[2] ?>" required>
    </div>

    <div class="mb-2">
        <label>Original Price</label>
        <input type="number" step="0.01" name="original" class="form-control"
               value="<?= $selected[3] ?>" required>
    </div>

    <div class="mb-2">
        <label>Retail Price</label>
        <input type="number" step="0.01" name="retail" class="form-control"
               value="<?= $selected[4] ?>" required>
    </div>

    <div class="mb-2">
        <label>Stock</label>
        <input type="number" name="stock" class="form-control"
               value="<?= $selected[5] ?>" required>
    </div>

    <button type="submit" name="submit" class="btn btn-success">
        Update Product
    </button>

    <a href="index.php" class="btn btn-dark">Back</a>

</form>

</div>

</body>
</html>