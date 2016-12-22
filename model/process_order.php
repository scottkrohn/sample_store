<?php
/**
 * Created by PhpStorm.
 * User: smk
 * Date: 12/19/2016
 * Time: 4:34 PM
 */

require_once('model/db_access.php');

function writeOrderToFile($order){
    $document_root = $_SERVER['DOCUMENT_ROOT'];
    $fp = fopen("orders/orders.txt", 'ab');
    if(!$fp){
        return false;
    }
    else{
        flock($fp, LOCK_EX);
        $output_string = $order->formattedOrderString();
        fwrite($fp, $output_string, strlen($output_string));
        flock($fp, LOCK_UN);
        fclose($fp);
    }
    return true;
}

function storeOrderDB($order){
    $db = new db_access();
    return $db->insertOrder($order);
}

