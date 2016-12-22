<?php
/**
 * Created by PhpStorm.
 * User: smk
 * Date: 12/21/2016
 * Time: 3:42 PM
 */

require_once('model/db_access.php');

class vieworders_controller {
    function viewAllAction(){
        $this->pageTitle = 'Helvete Record Store';
        $this->pageSubtitle = 'View All Orders';
        $db = new db_access();
        $this->all_orders = $db->getAllOrders();
        include('view/view_orders.php');
    }
}