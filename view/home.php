<!DOCTYPE html>
<html>
<head>
    <?php include('header.php') ?>
</head>
<body>

<?php include('navigation.php') ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2><?php echo $this->pageTitle ?></h2>
                <h4><?php echo $this->pageSubtitle ?></h4>
            </div>
        </div>
    </div>
</body>
</html>

<script type="text/javascript">
    $(document).ready(function(){
        $('.nav').find('#home').parent().addClass('active');
    });
</script>