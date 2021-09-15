<?php
/* config.php */
return array (
  'version' => '4.0.3',
  'web_title' => 'Repair',
  'web_description' => 'ระบบบันทึกข้อมูลงานแจ้งซ่อม',
  'timezone' => 'Asia/Bangkok',
  'member_status' => 
  array (
    0 => 'พนักงานทั่วไป',
    1 => 'ผู้ดูแลระบบ',
    2 => 'แผนกช่างซ่อม',
    3 => 'แผนก IT',
    4 => 'แผนกบัญชี',
  ),
  'color_status' => 
  array (
    0 => '#259B24',
    1 => '#FF0000',
    2 => '#0E0EDA',
    3 => NULL,
    4 => '#880E4F',
  ),
  'default_icon' => 'icon-tools',
  'inventory_w' => 600,
  'repair_first_status' => 1,
  'repair_job_no' => 'JOB%04d',
  'password_key' => '6108a7198e99c',
  'user_forgot' => 0,
  'user_register' => 0,
  'welcome_email' => 0,
  'member_only' => 1,
  'demo_mode' => 0,
  'login_fields' => 
  array (
    0 => 'username',
  ),
  'facebook_appId' => '',
  'google_client_id' => '',
  'bg_color' => '#356FC9',
  'color' => '#FFFFFF',
  'line_api_key' => 'RQVFlC5fO1mRxl0lvam35VuGjkJtqQU1UmrDAfzAKTs',
  'api_url' => 'http://localhost/inventory-main/api.php',
  'api_token' => 'RQVFlC5fO1mRxl0lvam35VuGjkJtqQU1UmrDAfzAKTs',
  'api_secret' => 'fb01728df6564',
  'api_ips' => 
  array (
  ),
  'modules' => 
  array (
    'inventory' => 1,
    'repair' => 1,
  ),
  'noreply_email' => 'webmaster@repairsystem.com',
  'email_charset' => 'utf-8',
  'email_Host' => 'localhost',
  'email_Port' => 25,
  'email_SMTPSecure' => '',
  'email_Username' => 'webmaster@repairsystem.com',
  'email_use_phpMailer' => 1,
  'email_SMTPAuth' => 1,
  'email_Password' => '1234',
);