<?php
/**
 * Created by PhpStorm.
 * User: smk
 * Date: 12/19/2016
 * Time: 4:48 PM
 */

require('db_access.php');

class Order{
    // ORDER INFORMATION
    private $item_counts; // Dictionary of all items ordered
    private $item_names;
    private $item_costs;
    private $order_time;

    // CUSTOMER INFORMATION
    private $buyer_name;
    private $buyer_address;


    public function __construct(){
        $this->item_counts = array();
        $this->item_costs = array();
        $this->item_names = array();
    }

    public static function createOrderWithArrays($counts, $names, $costs){
        $instance = new self();
        $instance->item_costs = $costs;
        $instance->item_counts = $counts;
        $instance->item_names = $names;
        return $instance;
    }

    public static function createOrderWithPost($request, $date){
        $instance = new self();
        $instance->extractProducts($request);
        $instance->extractBuyerInfo($request);
        $instance->order_time = $date;
        return $instance;
    }


    public function getCounts(){
        return $this->item_counts;
    }

    public function getTotalCost(){
        $keys = array_keys($this->item_costs);
        $total = 0;
        foreach($keys as $key){
            $total += $this->item_costs[$key] * $this->item_counts[$key];
        };
        return $total;
    }

    public function getItemIDs(){
        return array_keys($this->item_names);
    }

    public function getItemName($key){
        return $this->item_names[$key];
    }

    public function getOrderTime(){
        return $this->order_time;
    }

    public function getItemCost($key){
        return $this->item_costs[$key];
    }

    public function getItemCount($key){
        return $this->item_counts[$key];
    }

    public function getBuyerName(){
        return $this->buyer_name;
    }

    public function getBuyerAddress(){
        return $this->buyer_address;
    }

    public function formattedOrderString(){
        $order_details = "";
        foreach($this->getItemIDs() as $key){
            $order_details .= $this->item_counts[$key].' '.$key.', ';
        }
        $formatted_string = $this->getOrderTime()."\t".$order_details.$this->getTotalCost()."\n";
        return $formatted_string;
    }

    /* PRIVATE FUNCTIONS */
    private function extractProducts($request){
        $db = new db_access();
        $products = $db->getAllProducts();
        $products->bind_result($id, $unit_cost, $name);

        while($products->fetch()){
            // If the numer of items orders is 0 for a product, skip it.
            if($request[$id] == 0){
                continue;
            }
            else{
                $this->item_counts[(string)$id] = $request[$id];    // Load the number sent through POST req
                $this->item_costs[(string)$id] = $unit_cost;
                $this->item_names[(string)$id] = $name;
            }

        }
    }

    private function extractBuyerInfo($request){
        $this->buyer_name = $request['buyer_name'];
        $this->buyer_address = $request['buyer_address'];
    }
};