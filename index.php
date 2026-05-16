<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

function money($amount){
    return "Rs " . number_format((float)$amount, 2);
}

/* ---------------- LOAD PRODUCTS ---------------- */
$file = "products.txt";
$products = [];

if(file_exists($file)){
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach($lines as $line){
        $p = explode(",", $line);

        if(count($p) < 6) continue;

        $products[] = $p;
    }
}

/* ---------------- SEARCH ---------------- */
$search = $_GET['search'] ?? '';

if($search != ""){
    $products = array_filter($products, function($p) use ($search){
        return stripos($p[2], $search) !== false;
    });
}

/* ---------------- STATS ---------------- */
$totalProducts = 0;
$totalStock = 0;
$lowStock = 0;

foreach($products as $p){

    $totalProducts++;

    $stock = (int)$p[5];

    $totalStock += $stock;

    if($stock < 5){
        $lowStock++;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FW BROTHERS MANAGEMENT</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background: linear-gradient(135deg,#eef2ff,#f8fbff,#e0f7fa);
            min-height:100vh;
            font-family: Arial;
        }

        .top-bar{
            background:white;
            padding:15px 20px;
            border-radius:15px;
            box-shadow:0 4px 12px rgba(0,0,0,0.1);
            margin-bottom:20px;
        }

        .stat-card{
            padding:20px;
            border-radius:15px;
            color:white;
            text-align:center;
            box-shadow:0 4px 12px rgba(0,0,0,0.1);
        }

        .table-box{
            background:white;
            padding:20px;
            border-radius:15px;
            box-shadow:0 4px 12px rgba(0,0,0,0.1);
        }

        .logo{
            height:45px;
            width:45px;
            margin-right:10px;
            border-radius:10px;
        }

        .btn{
            border-radius:10px;
        }
    </style>
</head>

<body class="p-4">

<div class="container">

<!-- TOP BAR -->
<div class="top-bar d-flex justify-content-between align-items-center">

    <div class="d-flex align-items-center">
        <img src="logo.png" class="logo">
        <h4 class="m-0">FW BROTHERS MANAGEMENT</h4>
    </div>

    <div>
        <a href="add_product.php" class="btn btn-success">+ Add Product</a>
        <a href="billing.php" class="btn btn-warning">Billing</a>
        <a href="view_bills.php" class="btn btn-info">History</a>
        <a href="sales_report.php" class="btn btn-primary">Sales</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

</div>

<!-- DASHBOARD -->
<div class="row mb-3">

    <div class="col-md-4">
        <div class="stat-card bg-primary">
            <h5>Total Products</h5>
            <h2><?= $totalProducts ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card bg-success">
            <h5>Total Stock</h5>
            <h2><?= $totalStock ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card bg-danger">
            <h5>Low Stock</h5>
            <h2><?= $lowStock ?></h2>
        </div>
    </div>

</div>

<!-- SEARCH -->
<form method="GET" class="mb-3">

<div class="input-group">

    <input type="text"
           name="search"
           class="form-control"
           placeholder="Search product name..."
           value="<?= $search ?>">

    <button class="btn btn-primary">Search</button>

    <a href="index.php" class="btn btn-secondary">Reset</a>

</div>

</form>

<!-- PRODUCTS TABLE -->
<div class="table-box">

<h4 class="mb-3">Products List</h4>

<table class="table table-hover table-bordered">

<tr class="table-dark">
    <th>ID</th>
    <th>Name</th>
    <th>Original Price</th>
    <th>Retail Price</th>
    <th>Stock</th>
    <th>Action</th>
</tr>

<?php foreach($products as $p){ ?>

<tr>

    <td><?= $p[0] ?></td>
    <td><?= $p[2] ?></td>
    <td><?= money($p[3]) ?></td>
    <td><?= money($p[4]) ?></td>
    <td><?= $p[5] ?></td>

    <td>
        <a href="update_product.php?id=<?= $p[0] ?>" class="btn btn-sm btn-primary">Edit</a>
        <a href="delete_product.php?id=<?= $p[0] ?>" class="btn btn-sm btn-danger">Delete</a>
    </td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>