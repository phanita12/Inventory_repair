<?php
/* database.php */
return array (
  'mysql' => 
          array (
            'dbdriver' => 'mysql',
            'username' => 'root',
            'password' => '31M@ssw0rd',
            'dbname' => 'repair',
            'prefix' => 'rp',
            'hostname' => 'localhost',
          ),
        /* ค่ากำหนดการเชื่อมต่อ ชุดที่ 2 */
    /*      'connection2' => array(
            'dbdriver' => 'mysql',
            'username' => 'root',
            'password' => '31M@ssw0rd',
            'dbname' => 'booking',
            'prefix' => 'booking',
            'hostname' => 'localhost',
            'port' => 3306,
            'prefix' => 'prefix2'
        ),*/
  'tables' => 
  array (
    'user'      => 'user',
    'category'  => 'category',
    'language'  => 'language',
    'repair'    => 'repair',
    'repair_status'   => 'repair_status',
    'inventory'       => 'inventory',
    'inventory_meta'  => 'inventory_meta',
    'inventory_items' => 'inventory_items',
    'line' => 'line',
    'reservation' => 'reservation',
    'reservation_data' => 'reservation_data',
    'rooms' => 'rooms',
    'rooms_meta' => 'rooms_meta',
    'repair' => 'repair',
  ),
  
    
);