<form method="get" class="mb-3">
    <input type="text" name="search" class="form-control" placeholder="Search product by name">
</form>

<?php
$search = "";
if(isset($_GET['search'])){
    $search = strtolower($_GET['search']);
    $products = array_filter($products, function($p) use ($search){
        return strpos(strtolower($p[1]), $search) !== false;
    });
}
?>
