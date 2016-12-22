<?php
/**
 * Created by PhpStorm.
 * User: smk
 * Date: 12/19/2016
 * Time: 1:55 PM
 */

class home_controller{
    function homeAction(){
        $this->pageSubtitle= 'Home';
        $this->pageTitle = 'Helvete Record Store';
        include('view/home.php');
    }

}
