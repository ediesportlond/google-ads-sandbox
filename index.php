<?php
session_start();

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/secrets.php";
require_once __DIR__ . "/src/autoload.php";

if(@$_GET["customer"] == 'getInfo'){
  GetCustomerInfo::getInfo();
}
