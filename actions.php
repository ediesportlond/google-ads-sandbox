<?php

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/secrets.php";
require_once __DIR__ . "/src/autoload.php";

function respond($response)
{
  header("Content-Type: application/json; charset=UTF-8");
  echo json_encode($response);
  exit();
}

if (@$_GET["customer"] === "getInfo") respond(GetCustomerInfo::getInfo());

if(@$_GET["keywords"] === "getKeywords"){
  respond(
    GenerateKeywordIdeas::getKeywords($_GET["keywordOne"], $_GET["keywordTwo"])
  );
}

if(@$_GET["keywords"] === "getStats"){
  respond(GetKeywordStats::getStats());
}

if(@$_GET["budget"] === "getBudgets"){
  respond(GetAccountBudgets::getBudgets());
}