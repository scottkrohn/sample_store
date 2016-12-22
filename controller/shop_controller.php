<?php
/**
 * Created by PhpStorm.
 * User: smk
 * Date: 12/20/2016
 * Time: 7:11 AM
 */

require_once('model/order_model.php');
require_once ('model/db_access.php');


class shop_controller{

    function shopAction(){
        $this->pageTitle = 'Helvete Record Store';
        $this->pageSubtitle= 'Shop';
        $dbaccess = new db_access();
        $this->products = $dbaccess->getAllProducts();
        include('view/shop.php');
    }

    function submit_orderAction(){
        $this->pageTitle = 'Helvete Record Store';
        $this->pageSubtitle = "Order Complete";
        $this->current_order = Order::createOrderWithPost($_POST, date('Y/m/d H:i:s'));
        require('model/process_order.php');

        if(storeOrderDB($this->current_order)){
            include('view/completed_order.php');
        }
    }
}