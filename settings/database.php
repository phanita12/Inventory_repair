<?php
/* database.php */
/*return array (
  'mysql' => 
  array (
    'dbdriver' => 'mysql',
    'username' => 'root',
    'password' => '31M@ssw0rd',
    'dbname' => 'repair',
    'prefix' => 'rp',
    'hostname' => 'localhost',
  ),
  'tables' => 
  array (
    'user' => 'user',
    'category' => 'category',
    'language' => 'language',
    'repair' => 'repair',
    'repair_status' => 'repair_status',
    'inventory' => 'inventory',
    'inventory_meta' => 'inventory_meta',
    'inventory_items' => 'inventory_items',
  ),*/

  return array (
    'mysql' => 
    array (
      'dbdriver' => 'mysql',
      'username' => 'root',
      'password' => '', //31M@ssw0rd
      'dbname' => 'it_management',
      'prefix' => 'pit',
      'hostname' => 'localhost',
    ),
    'tables' => 
    array (
      'user' => 'repair_user',
      'category' => 'repair_category',
      'language' => 'repair_language',
      'repair' => 'repair',
      'repair_status' => 'repair_status',
      'inventory' => 'repair_inventory',
      'inventory_meta' => 'repair_inventory_meta',
      'inventory_items' => 'repair_inventory_items',
    ),
);