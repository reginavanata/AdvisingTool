<?php

echo "This is now a php page.";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Admin Panel</title>
</head>
<body>
<h1>Advise-It Admin Panel</h1>

<h2>This is the admin panel</h2>


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

    <?php
    foreach ($_SESSION['retrievedPlans'] as $row) {

        $uniqueID = $row[0]['user_id'];
        $lastUpdated = $row[0]['last_updated'];
        $advisor = $row[0]['advisor'];


        echo "
                   <tr>          
                        <td>$uniqueID</td>
                        <td>$lastUpdated</td>
                        <td>$advisor</td>
                    </tr>";
    }
    ?>
    </tbody>
</table>


</body>
</html>