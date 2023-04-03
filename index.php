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

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>API Endpoint Tester</title>
</head>
<body>
  <h1>API Endpoint Tester</h1>
  <p>This page allows you to test various API endpoints by clicking the links below. Each link is associated with a specific endpoint, and parameters can be modified in the URL if necessary.</p>

  <h2>1. Get Customer Info</h2>
  <p>Endpoint: <code>actions.php?customer=getInfo</code></p>
  <p>Description: Retrieves customer information.</p>
  <p><a href="index.php?customer=getInfo">Test Get Customer Info</a></p>

  <h2>2. Generate Keyword Ideas</h2>
  <p>Endpoint: <code>index.php?keywords=getKeywords&keywordOne=KEYWORD1&keywordTwo=KEYWORD2</code></p>
  <p>Description: Generates keyword ideas based on the provided seed keywords.</p>
  <p>Parameters:</p>
  <ul>
    <li><code>keywordOne</code> (required): The first seed keyword.</li>
    <li><code>keywordTwo</code> (required): The second seed keyword.</li>
  </ul>
  <p><a href="index.php?keywords=getKeywords&keywordOne=example&keywordTwo=test">Test Generate Keyword Ideas</a></p>

  <h2>3. Get Keyword Stats</h2>
  <p>Endpoint: <code>index.php?keywords=getStats</code></p>
  <p>Description: Retrieves statistics for the keywords in the account.</p>
  <p><a href="index.php?keywords=getStats">Test Get Keyword Stats</a></p>

  <h2>4. Get Account Budgets</h2>
  <p>Endpoint: <code>index.php?budget=getBudgets</code></p>
  <p>Description: Retrieves the budgets for the account.</p>
  <p><a href="index.php?budget=getBudgets">Test Get Account Budgets</a></p>
</body>
</html>
