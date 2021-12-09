<?php
/* database.php */
  return array (
    'mysql' => 
    array (
      'dbdriver' => 'mysql',
      'username' => 'root',
      'password' => '',//31M@ssw0rd
      'dbname' => 'it_management',
      'prefix' => 'pit',
      'hostname' => 'localhost',
    ),
    'tables' => 
    array (
      'repair' => 'repair',
      'category' => 'repair_category',
      'inventory' => 'repair_inventory',
      'inventory_items' => 'repair_inventory_items',
      'inventory_meta' => 'repair_inventory_meta',
      'language' => 'repair_language',
      'number' => 'repair_number',
      'repair_status' => 'repair_status',
      'user' => 'user',
    ),
);