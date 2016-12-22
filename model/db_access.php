<?php
/**
 * Created by PhpStorm.
 * User: smk
 * Date: 12/20/2016
 * Time: 2:56 PM
 */

require_once('order_model.php');
require_once('buyer_model.php');

class db_access {

    public function getAllProducts(){
        $db = $this->connectDB();
        $query = "SELECT id, unit_cost, name FROM products";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    private function connectDB(){

        //TODO: NOTE: username and PW removed for VC
        //$db = new mysqli('localhost', USERNAME, PASSWORD, 'helvete');
        return $db;
    }

    // SELECTING FUNCTIONS

    public function getProductCost($id){
        $db = $this->connectDB();
        $query = "SELECT unit_cost FROM products WHERE id = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($cost);
        $stmt->fetch();
        return $cost;
    }

    // Query the DB for the name of a customer, return false if not found.
    public function getBuyer($name){
        $db = $this->connectDB();
        $query = "SELECT name FROM buyer WHERE name = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result();

        // If the result set is 0, the person wasn't found.
        if($stmt->num_rows == 0){
            return false;
        }

        $stmt->bind_result($found_name);
        $stmt->fetch();
        return $found_name;

    }

    public function getAllOrders(){
        // Get an array of all transactions and an array of the transaction IDs
        $transactions = $this->getAllTransactions();
        $trans_ids = array_keys($transactions);

        $orders = array();
        // For each transaction ID, find all associated products purchased
        foreach($trans_ids as $id){
            $item_counts = array();
            $item_names = array();
            $item_costs = array();

            // Dictionary with product_ids as keys, quantities as values.
            $contains = $this->getContains($id);
            // Get an array of product IDs associated with this transaction.
            $contains_product_ids = array_keys($contains);
            // For each product in this purchase, get the name, quantity and cost.
            foreach($contains_product_ids as $pid){
                $current_product = $this->getProduct($pid);
                $item_counts[$pid] = $contains[$pid];   // load the count of the product
                $item_costs[$pid] = $current_product['unit_cost'];
                $item_names[$pid] = $current_product['name'];
                // Create an order with the current transaction information.
                $current_order = Order::createOrderWithArrays($item_counts, $item_names, $item_costs);
                $orders[] = $current_order;
            }
        }
        return $orders;
    }
    // TODO: finish above method to get orders from database and display them on webpage.

    private function getProduct($id){
        $db = $this->connectDB();
        $query = "SELECT unit_cost, name FROM products WHERE id = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($cost, $name);

        $result = array('unit_cost' => $cost, 'name' => $name);
        return $result;
    }

    private function getAllTransactions(){
        $db = $this->connectDB();
        $query = "SELECT id, buyer_name FROM transaction";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $buyer);

        $result = array();
        while($stmt->fetch()){
            $result[$id] = $buyer;
        }
        return $result;
    }

    private function getContains($transaction_id){
         $db = $this->connectDB();
         $query = "SELECT product_id, quantity FROM contains WHERE transaction_id = ?;";
         $stmt = $db->prepare($query);
         $stmt->bind_param('i', $transaction_id);
         $stmt->execute();
         $stmt->store_result();
         $stmt->bind_result($pid, $qty);
         $result = array();
         while($stmt->fetch()){
             $result[$pid] = $qty;
         }
         return $result;
    }

    // INSERTING FUNCTIONS

    public function insertOrder($order){
        // If the customer doesn't exist
        if(!$this->getBuyer($order->getBuyerName())){
            // If adding the customer fails for any reason, return false.
            if(!$this->insertBuyer($order->getBuyerName(), $order->getBuyerAddress())){
                return false;
            }
        }

        // Insert transaction tuple
        $current_transaction_id = $this->getNextTransactionID();
        // If adding the transaction fails, return false.
        if(!$this->insertTransaction($current_transaction_id, $order->getBuyerName())){
            return false;
        }

        // Insert 'contains' table tuples for each product purchased.
        $item_ids = $order->getItemIDs();
        foreach($item_ids as $id){
            $quantity = $order->getItemCount($id);
            $this->insertContains($current_transaction_id, $id, $quantity);
        }
        return true;
    }


    // Insert a buyer into the database.
    private function insertBuyer($name, $address){
        $db = $this->connectDB();
        $query = "INSERT INTO buyer (name, order_count, address) values (?, ?, ?);";
        $stmt = $db->prepare($query);

        $order_count = 1;
        $stmt->bind_param('sis', $name, $order_count, $address);   // Set order_count to 1 since we're adding an order.
        $stmt->execute();

        // Check if the insert was successful.
        if($stmt->affected_rows > 0){
            return true;
        }
        else{
            $this->errorLogger($stmt->error." in insertBuyer");
            return false;
        }
    }

    private function insertTransaction($id, $name){
        $db = $this->connectDB();
        $query = "INSERT INTO transaction (id, buyer_name) values (?, ?);";
        $stmt = $db->prepare($query);
        $stmt->bind_param('is', $id, $name);
        $stmt->execute();
        if($stmt->affected_rows > 0){
            return true;
        }
        else{
            $this->errorLogger($stmt->error." in insertTransaction: ".$id);
            return false;
        }
    }

    // Check what the next transaction ID will be.
    private function getNextTransactionID(){
        $db = $this->connectDB();
        $query = "SELECT MAX(id) as curr_max_id FROM transaction;";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($curr_max_id);
        $stmt->fetch();
        // Return the current largest id plus 1 as the new ID.
        return $curr_max_id + 1;
    }

    private function insertContains($transaction_id, $product_id, $quantity){
        $db = $this->connectDB();
        $query = "INSERT INTO contains (transaction_id, product_id, quantity) values (?, ?, ?);";
        $stmt = $db->prepare($query);
        $stmt->bind_param('iii', $transaction_id, $product_id, $quantity);
        $stmt->execute();

        if($stmt->affected_rows > 0){
            return true;
        }
        else {
            $this->errorLogger($stmt->error);
            $this->errorLogger($this->getNextTransactionID());
            return false;
        }
    }

    private function errorLogger($error){
        $file = fopen('logs/log.txt', 'ab');
        if($file){
            flock($file, LOCK_EX);
            fwrite($file, $error, strlen($error));
            flock($file, LOCK_UN);
            fclose($file);
        }
    }

}