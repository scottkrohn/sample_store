<?php
/**
 * Created by PhpStorm.
 * User: smk
 * Date: 12/19/2016
 * Time: 1:43 PM
 */

require('controller/home_controller.php');
require('controller/shop_controller.php');

if(isset($_POST['action'])){
    $action = $_POST['action'];
}
else if(isset($_GET['action'])){
    $action = $_GET['action'];
}
else{
    $action = 'home';
}

if(isset($_GET['class'])){
    $class = $_GET['class'];
}
else if(isset($_POST['class'])){
    $class = $_POST['class'];
}
else{
    $class = 'home';
}


$className = $class.'_controller';
$controller = new $className;
$actionName = $action.'Action';
$controller->$actionName();


//TODO: Add Order to DB upon submit.
//TODO: Add customer to DB upon order submit.
//TODO: Ability to add new records to store.
//TODO: List all customers

