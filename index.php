<?php
session_start();

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/secrets.php";
require_once __DIR__ . "/src/autoload.php";

include_once("./views/home.php");

//Tested and working
if (@$_GET["getInfo"] == 'customer') {
  GetCustomerInfo::getInfo();
}

/**
 * Generates keyword ideas and returns monthly searches.
 * Currently set to United States for location and English language.
 * @param kewords boolean
 * @param kewyWordOne
 * @param keywordTwo
 * @return html Formatted strings with keywords and monthly searches.
 */
if (@$_GET['keywords']) {
  GenerateKeywordIdeas::main($_GET["keywordOne"], $_GET["keywordTwo"]);
}

// Need to test with live account

if (@$_GET["getStats"] == 'keywords') {
  GetKeywordStats::main();
}

if (@$_GET["getInfo"] == 'budget') {
  GetAccountBudgets::main();
}

include_once("./views/footer.php");