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
  <p><a href="actions.php?customer=getInfo">Test Get Customer Info</a></p>

  <h2>2. Generate Keyword Ideas</h2>
  <p>Endpoint: <code>actions.php?keywords=getKeywords&keywordOne=KEYWORD1&keywordTwo=KEYWORD2</code></p>
  <p>Description: Generates keyword ideas based on the provided seed keywords.</p>
  <p>Parameters:</p>
  <ul>
    <li><code>keywordOne</code> (required): The first seed keyword.</li>
    <li><code>keywordTwo</code> (required): The second seed keyword.</li>
  </ul>
  <p><a href="actions.php?keywords=getKeywords&keywordOne=example&keywordTwo=test">Test Generate Keyword Ideas</a></p>

  <h2>3. Get Keyword Stats</h2>
  <p>Endpoint: <code>actions.php?keywords=getStats</code></p>
  <p>Description: Retrieves statistics for the keywords in the account.</p>
  <p><a href="actions.php?keywords=getStats">Test Get Keyword Stats</a></p>

  <h2>4. Get Account Budgets</h2>
  <p>Endpoint: <code>actions.php?budget=getBudgets</code></p>
  <p>Description: Retrieves the budgets for the account.</p>
  <p><a href="actions.php?budget=getBudgets">Test Get Account Budgets</a></p>
</body>
</html>
