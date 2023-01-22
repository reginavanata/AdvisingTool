<?php

echo "This is now a php page.";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="styles/styles.css">

  <title>Admin Panel</title>
</head>
<body>
testing
<h1>Advise-It Admin Panel</h1>

<h2>This is the admin panel</h2>

<h1>


</h1>

<form action="#" method="post" >
  <button type="submit">Display All Created Plans</button>
</form>

<table id='all-plans'>
    <thead>
    <tr>
        <th>Unique ID</th>
        <th>Last Updated</th>
        <th>Advisor</th>
    </tr>
    </thead>

    <tbody>

    {{@SESSION.retrievedPlan[0]['fall']}}

    <repeat group="{{ @SESSION.retrievedPlans }}" value="{{ @plan }}">
        <tr>
            <td>{{ @plan['user_id'] }}</td>
            <td>{{ @plan['last_updated'] }}</td>
            <td>{{ @plan['advisor'] }}</td>
        </tr>
    </repeat>
    </tbody>
</table>


</body>
</html>