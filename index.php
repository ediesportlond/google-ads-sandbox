<?php
session_start();

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/secrets.php";
require_once __DIR__ . "/src/autoload.php";

if (@$_GET["getInfo"] == 'customer') {
  GetCustomerInfo::getInfo();
}

if (@$_GET["getStats"] == 'keywords') {
  GetKeywordStats::main();
}

if (@$_GET["getInfo"] == 'budget') {
  GetAccountBudgets::main();
}

if (@$_GET['keywords']) {
  GenerateKeywordIdeas::main($_GET["keywordOne"], $_GET["keywordTwo"]);
}
