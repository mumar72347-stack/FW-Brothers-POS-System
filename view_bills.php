<?php
$file = "bills.txt";

/* CLEAR HISTORY */
if(isset($_POST['clear'])){
    file_put_contents($file, "");
}

/* READ FILE */
$content = "";
$totalSales = 0;
$totalProfit = 0;

if(file_exists($file)){
    $content = file_get_contents($file);

    preg_match_all('/Grand Total:\s*Rs\s*([0-9,]+)/', $content, $sales);
    preg_match_all('/Profit:\s*Rs\s*([0-9,]+)/', $content, $profits);

    foreach($sales[1] as $s){
        $totalSales += (int)str_replace(",", "", $s);
    }

    foreach($profits[1] as $p){
        $totalProfit += (int)str_replace(",", "", $p);
    }
}

/* FILTER INPUT */
$month = $_GET['month'] ?? '';
$year  = $_GET['year'] ?? '';

$filteredSales = 0;
$filteredProfit = 0;

if($month || $year){

    preg_match_all('/Date:\s*([0-9]{4})-([0-9]{2})-[0-9]{2}/', $content, $dates, PREG_SET_ORDER);

    preg_match_all('/Grand Total:\s*Rs\s*([0-9,]+)/', $content, $salesArr);
    preg_match_all('/Profit:\s*Rs\s*([0-9,]+)/', $content, $profitArr);

    $index = 0;

    foreach($dates as $d){

        $y = $d[1];
        $m = $d[2];

        $match = true;

        if($year && $year != $y) $match = false;
        if($month && $month != $m) $match = false;

        if($match){

            $filteredSales += (int)str_replace(",", "", $salesArr[1][$index] ?? 0);
            $filteredProfit += (int)str_replace(",", "", $profitArr[1][$index] ?? 0);
        }

        $index++;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Billing History</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{ background:#f4f6f9; }

        .stat-card{
            padding:20px;
            border-radius:15px;
            color:white;
            text-align:center;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
        }

        .history-box{
            background:white;
            padding:20px;
            border-radius:15px;
            margin-bottom:15px;
            border-left:6px solid #0d6efd;
        }

        .date-box{
            background:#0d6efd;
            color:white;
            padding:6px 12px;
            border-radius:10px;
            display:inline-block;
            font-weight:bold;
        }

        .total-box{
            background:#198754;
            color:white;
            padding:10px;
            border-radius:10px;
            margin-top:10px;
        }

        .profit-box{
            background:#6f42c1;
            color:white;
            padding:10px;
            border-radius:10px;
            margin-top:10px;
        }
    </style>
</head>

<body class="p-4">

<div class="container">

<h2>Billing History</h2>

<a href="index.php" class="btn btn-dark mb-3">Back</a>

<!-- DASHBOARD -->
<div class="row mb-3">

    <div class="col-md-6">
        <div class="stat-card bg-primary">
            <h5>Total Sales (All Time)</h5>
            <h2>Rs <?= number_format($totalSales) ?></h2>
        </div>
    </div>

    <div class="col-md-6">
        <div class="stat-card bg-success">
            <h5>Total Profit (All Time)</h5>
            <h2>Rs <?= number_format($totalProfit) ?></h2>
        </div>
    </div>

</div>

<!-- FILTER -->
<form method="GET" class="mb-3">
<div class="row">

    <div class="col-md-4">
        <select name="month" class="form-control">
            <option value="">Select Month</option>
            <?php
            for($m=1;$m<=12;$m++){
                $mm = str_pad($m,2,"0",STR_PAD_LEFT);
                echo "<option value='$mm' ".($month==$mm?'selected':'').">$mm</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-4">
        <input type="text" name="year" class="form-control"
               placeholder="Enter Year (e.g. 2026)"
               value="<?= $year ?>">
    </div>

    <div class="col-md-4">
        <button class="btn btn-primary w-100">Filter</button>
    </div>

</div>
</form>

<!-- FILTER RESULT -->
<?php if($month || $year){ ?>
<div class="row mb-3">

    <div class="col-md-6">
        <div class="stat-card bg-warning text-dark">
            <h5>Filtered Sales</h5>
            <h2>Rs <?= number_format($filteredSales) ?></h2>
        </div>
    </div>

    <div class="col-md-6">
        <div class="stat-card bg-danger">
            <h5>Filtered Profit</h5>
            <h2>Rs <?= number_format($filteredProfit) ?></h2>
        </div>
    </div>

</div>
<?php } ?>

<!-- CLEAR -->
<form method="post">
    <button name="clear" class="btn btn-danger mb-3">Clear History</button>
</form>

<?php
if(!file_exists($file) || filesize($file)==0){
    echo "<div class='alert alert-info'>No Bills Found</div>";
    exit;
}

$bills = preg_split('/={10,}/', $content);
?>

<?php foreach(array_reverse($bills) as $bill){ 

$bill = trim($bill);
if($bill=="") continue;
?>

<div class="history-box">

<?php
$lines = explode("\n",$bill);

foreach($lines as $line){

    $line = trim($line);
    if($line=="") continue;

    if(strpos($line,"Date:")!==false){
        echo "<div class='date-box'>$line</div>";
    }
    elseif(strpos($line,"Grand Total")!==false){
        echo "<div class='total-box'>$line</div>";
    }
    elseif(strpos($line,"Profit")!==false){
        echo "<div class='profit-box'>$line</div>";
    }
    else{
        echo "<div>$line</div>";
    }
}
?>

</div>

<?php } ?>

</div>

</body>
</html>