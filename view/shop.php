<!DOCTYPE html>
<html>
<head>
    <?php include('header.php'); ?>
    <style>

    </style>
</head>
    <?php include('navigation.php'); ?>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2><?php echo $this->pageTitle ?> </h2>
                <h4><?php echo $this->pageSubtitle ?></h4>
                <hr>
            </div>
        </div>
    </div>

    <div class="row" id="test">
        <div class="col-sm-6 col-sm-offset-3 text-center">
            <form class=form-horizontal" method="post" action="index.php?action=submit_order&class=shop">
                <div class="col-sm-12">
                    <h4>Product Information</h4>
                    <hr>
                </div>
                <?php
                    $this->products->bind_result($id, $unit_cost, $name);
                    while($this->products->fetch()) {
                        echo '<div class="form-group">';
                        echo '<label for="'.$id.'" class="col-sm-6 text-right">'.$name.'</label>';
                        echo '<div class="col-sm-6">';
                        echo '<input type="number" class="form-control" id="'.$id.'" name="'.$id.'">';
                        echo '</div>';
                    }
                ?>
                <div class="col-sm-12 add-margin-top">
                    <h4>Customer Information</h4>
                    <hr>
                </div>
                <div class="form-group">
                    <label for="buyer_name" class="col-sm-4 text-right">Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="buyer_name" id="customer_name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="buyer_address" class="col-sm-4 text-right">Address</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="buyer_address" id="customer_address">
                    </div>
                </div>
                <button type="submit" id='submit_button' class="btn btn-default col-sm-8 col-sm-offset-4 add-margin-top">Submit Order</button>
            </form>
        </div>
    </div>
</body>
</html>


<script type="text/javascript">
    $(document).ready(function(){
        $('.nav').find('#shop').parent().addClass('active');
    });

    // Validate input.
    $('#submit_button').on('click', function(){

    });
</script>
