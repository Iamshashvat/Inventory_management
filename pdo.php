<?php
     session_start();
     $pdo = new PDO('mysql:host=localhost; port=3306; dbname=inventorydata', 'root', 'TrippleAa');
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
?>