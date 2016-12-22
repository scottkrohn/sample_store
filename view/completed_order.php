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
                <h2><?php echo $this->pageTitle ?></h2>
                <h4><?php echo $this->pageSubtitle ?></h4>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4 col-sm-offset-4 text-center add-margin-top">
                <h3>Order Details:</h3>
                <p><b>Order Time:</b> <?php echo $this->current_order->getOrderTime() ?></p>
                <div id="order_details">
                    <p>
                        <?php
                            foreach($this->current_order->getItemIDs() as $item){
                                echo '<b>'.$this->current_order->getItemName($item).'</b>: '.$this->current_order->getItemCount($item).' @ '.$this->current_order->getItemCost($item).' ea<br />';
                            }
                        ?>
                    </p>
                    <p>
                        <h4>Total Order Cost: <?php echo $this->current_order->getTotalCost() ?></h4>
                    </p>
                    <h4>Shipping Info:</h4>
                    <b>Name: </b><?php echo $this->current_order->getBuyerName() ?>
                    <br/>
                    <b>Address: </b><?php echo $this->current_order->getBuyerAddress() ?>
                </div>
                <hr>
            </div>
        </div>
    </div>
</body>
</html>