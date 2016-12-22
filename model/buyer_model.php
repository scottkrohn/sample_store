<?php
/**
 * Created by PhpStorm.
 * User: smk
 * Date: 12/21/2016
 * Time: 3:49 PM
 */

class Buyer {
    private $name;
    private $address;

    public function __construct($name="", $address="") {
        $this->address = $address;
        $this->name = $name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setAddress($address){
        $this->address = $address;
    }

    public function getAddress(){
        return $this->address;
    }

    public function getName(){
        return $this->name;
    }
}