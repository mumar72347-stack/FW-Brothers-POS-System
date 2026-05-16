<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

$file = "bills.txt";
$searchMonth = $_GET['month'] ?? '';
$searchYear = $_GET['year'] ?? '';

$totalSales = 0;
$monthlySales = 0;
$yearlySales = 0;

function getGrandTotal($bill){
    preg_match('/Grand Total:\s*([0-9.]+)/', $bill, $matches);
    return $matches[1] ?? 0;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f4f6f9;
        }

        .box{
            background:white;
            padding:20px;
            border-radius:15px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
            margin-bottom:15px;
        }

        .stat{
            padding:15px;
            border-radius:10px;
            color:white;
            font-weight:bold;
        }

        .blue{background:#0d6efd;}
        .green{background:#198754;}
        .orange{background:#fd7e14;}
    </style>
</head>

<body class="p-4">

<div class="container">

<h2>Sales Report</h2>

<a href="index.php" class="btn btn-dark mb-3">Back</a>

<!-- FILTER -->
<form method="GET" class="box">
    <div class="row">
        <div class="col-md-5">
            <input type="number" name="month" class="form-control" placeholder="Month (1-12)" value="<?= $searchMonth ?>">
        </div>

        <div class="col-md-5">
            <input type="number" name="year" class="form-control" placeholder="Year (2026)" value="<?= $searchYear ?>">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </div>
</form>

<?php

if(file_exists($file)){

    $content = file_get_contents($file);
    $bills = explode("====================================", $content);

    foreach($bills as $bill){

        $bill = trim($bill);
        if($bill == "") continue;

        // DATE FILTER
        preg_match('/Date:\s*(\d{4})-(\d{2})-(\d{2})/', $bill, $dateMatch);

        if(isset($dateMatch[0])){

            $year = $dateMatch[1];
            $month = $dateMatch[2];

            $grand = getGrandTotal($bill);
            $totalSales += $grand;

            if($searchYear == '' && $searchMonth == ''){
                $monthlySales += $grand;
                $yearlySales += $grand;
            }
            else{

                if($searchYear != '' && $year == $searchYear){
                    $yearlySales += $grand;
                }

                if($searchMonth != '' && $month == $searchMonth){
                    $monthlySales += $grand;
                }
            }
        }
    }
}

?>

<!-- STATS -->
<div class="row">

    <div class="col-md-4">
        <div class="stat blue">
            Total Sales: <?= $totalSales ?>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat green">
            Monthly Sales: <?= $monthlySales ?>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat orange">
            Yearly Sales: <?= $yearlySales ?>
        </div>
    </div>

</div>

</div>

</body>
</html>