<?php

    require('init.php');
    require('api.php');
    require('db.php');
    
    $db = Db::Create('localhost','root','','testdb');
    
    $GLOBALS['DB'] = $db->Capsule;

?>