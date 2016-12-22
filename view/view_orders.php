<!DOCTYPE html>
<html>
<head>
    <?php include('header.php'); ?>
</head>
<body>
    <?php include('navigation.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2><?php echo $this->pageTitle ?> </h2>
                <h4><?php echo $this->pageSubtitle ?></h4>
                <hr>
            </div>
        </div>

        <div class="col-sm-6 col-sm-offset 3">
            <h4>Orders:</h4>
            <?php
                foreach($this->all_orders as $order){
                    echo "<br>ORDER<br>";
                    $products_in_order = $order->getItemIDs();
                    $names = $order->getAllItemNames();
                    $costs = $order->getAllItemCosts();
                    $counts = $order->getAllItemCounts();
                    foreach($products_in_order as $product){
                        echo "Item: ".$product."\t".$names[$product]."\t<b>QTY</b>: ".$counts[$product]."<br>";
                    }
                }
            ?>
        </div>
    </div>
</body>
</html>