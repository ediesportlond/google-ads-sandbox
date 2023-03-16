<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Documentation</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <h1>Working Endpoints</h1>
  <table>
    <thead>
      <tr>
        <td>Endpoint</td>
        <td>Returns</td>
        <td>Params</td>
        <td>Notes</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>getInfo</td>
        <td>Basic info on current user</td>
        <td>keywords=customer</td>
        <td></td>
      </tr>
      <tr>
        <td>keywords</td>
        <td>Formatted strings with keywords and monthly searches.</td>
        <td>keywords=1, keywordOne, keywordTwo</td>
        <td></td>
      </tr>
    </tbody>
  </table>
  <p>Sample query string: <em>?keywords=1&keywordOne=rimworld&keywordTwo=indie</em></p>
  <h2>Do a keyword search</h2>
  <div class="keyword-form">
    <label for="keywordOne">Keyword One
      <input name="keywordOne" type="text">
    </label>
    <label for="keywordTwo">Keyword Two
      <input name="keywordTwo" type="text">
    </label>
    <button>Query</button>
</div>