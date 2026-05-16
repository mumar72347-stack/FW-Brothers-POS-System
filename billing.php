<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

/* ---------------- MONEY FORMAT ---------------- */
function rs($amount){
    return "Rs " . number_format((float)$amount, 0);
}

/* ---------------- LOAD PRODUCTS ---------------- */
$file = "products.txt";
$products = [];

if(file_exists($file)){
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach($lines as $line){
        $p = explode(",", $line);

        // safety check (IMPORTANT)
        if(count($p) < 6) continue;

        $products[] = $p;
    }
}

/* ---------------- CLEAR (optional cart if used later) ---------------- */
if(isset($_POST['clear'])){
    $_SESSION['cart'] = [];
}

/* ---------------- GENERATE BILL ---------------- */
if(isset($_POST['bill'])){

    $date = date("Y-m-d H:i:s");

    $billData  = "\n====================================\n";
    $billData .= "Date: $date\n";
    $billData .= "------------------------------------\n";

    $updated = [];
    $grand = 0;
    $profit = 0;

    foreach($products as $p){

        $id = $p[0];
        $barcode = $p[1];
        $name = $p[2];

        $original = (float)$p[3];
        $retail   = (float)$p[4];
        $stock    = (int)$p[5];

        $qty = isset($_POST['qty'][$id]) ? (int)$_POST['qty'][$id] : 0;

        if($qty > 0 && $qty <= $stock){

            $total = $retail * $qty;
            $pft   = ($retail - $original) * $qty;

            $grand  += $total;
            $profit += $pft;

            $stock -= $qty;

            $billData .= "$name | Qty:$qty | Total:" . rs($total) . " | Profit:" . rs($pft) . "\n";
        }

        $updated[] = "$id,$barcode,$name,$original,$retail,$stock";
    }

    $billData .= "------------------------------------\n";
    $billData .= "Grand Total: " . rs($grand) . "\n";
    $billData .= "Profit: " . rs($profit) . "\n";
    $billData .= "====================================\n";

    file_put_contents("bills.txt", $billData, FILE_APPEND);
    file_put_contents("products.txt", implode("\n", $updated));

    $success = "Bill Generated Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Billing System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f4f6f9;
            font-family:Arial;
        }

        .box{
            background:white;
            padding:20px;
            border-radius:15px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
            margin-bottom:15px;
        }

        .total{
            background:#198754;
            color:white;
            padding:12px;
            border-radius:10px;
            font-weight:bold;
            text-align:center;
        }
    </style>
</head>

<body class="p-4">

<div class="container">

<h3>Billing System</h3>

<a href="index.php" class="btn btn-dark mb-3">Back</a>

<?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

<!-- BILL FORM -->
<div class="box">

<h5>Manual Billing</h5>

<form method="post">

<table class="table table-bordered">

<tr class="table-dark">
    <th>Name</th>
    <th>Original</th>
    <th>Retail</th>
    <th>Stock</th>
    <th>Qty</th>
</tr>

<?php foreach($products as $p){ ?>

<tr>
    <td><?= $p[2] ?></td>
    <td><?= rs($p[3]) ?></td>
    <td><?= rs($p[4]) ?></td>
    <td><?= $p[5] ?></td>
    <td>
        <input type="number"
               name="qty[<?= $p[0] ?>]"
               class="form-control"
               min="0"
               max="<?= $p[5] ?>"
               value="0">
    </td>
</tr>

<?php } ?>

</table>

<button name="bill" class="btn btn-success w-100">
    Generate Bill
</button>

</form>

</div>

</div>

</body>
</html>