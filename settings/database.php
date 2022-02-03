<?php
/* database.php */
  return array (
    'mysql' => 
    array (
      'dbdriver' => 'mysql',
      'username' => 'root',
<<<<<<< HEAD
      'password' => '', //31M@ssw0rd
      'dbname' => 'it_management',
=======
      'password' => '',//31M@ssw0rd
      'dbname' => 'it_management', // it_management
>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
      'prefix' => 'pit',
      'hostname' => 'localhost',
    ),
    'tables' => 
    array (
      'line' => 'line',
      'user' => 'user',
      'category' => 'carbooking_category',
      'language' => 'carbooking_language',
      'repair' => 'carbooking',
      'repair_status' => 'carbooking_status',
      'inventory' => 'carbooking_inventory',
      'inventory_meta' => 'carbooking_inventory_meta',
      'inventory_items' => 'carbooking_inventory_items',
      'number' => 'carbooking_number',
     
    ),    
    /*array (
      'repair' => 'repair',
      'category' => 'repair_category',
      'inventory' => 'repair_inventory',
      'inventory_items' => 'repair_inventory_items',
      'inventory_meta' => 'repair_inventory_meta',
      'language' => 'repair_language',
      'number' => 'repair_number',
      'repair_status' => 'repair_status',
      'user' => 'user',
    ),*/
);