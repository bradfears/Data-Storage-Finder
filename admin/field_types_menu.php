<?php

session_start();

require_once('../functions.php');

if (!isset($_SESSION['user'])) {
  header("location:login.php");
}

if(isset($_POST['token'])) {

    $data = json_decode($_POST['data']);
    $counter = 1;
    foreach($data as $key=>$val)
    {
        saveFieldTypesForMenu($val, $counter);
        $counter++;        
    }   

}

echo '
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Navigation Menu</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">  
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script src="field_types_menu.js"></script>
  <script>
  $(function() {
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
  });
  </script>
  <style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
    #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
    #sortable li span { position: absolute; margin-left: -1.3em; }
    #save-reorder{ padding:10px; }
    .wrapper{width:60%; margin0 auto;}
    .container {
      width: 90%;
      margin-left: 5%;
    }
  </style>
</head>
<body>
<div class="container">
<br>
These are the field types that are displayed on the main page when a user clicks an item.<br>
Click and drag field types into the desired order.<br>
If you make changes, do NOT forget to click SAVE!
<p>&nbsp;</p>
';

echo '
  <ul id="sortable">';
      
      $data = getFieldTypesForMenu();
      foreach($data as $record) {
        $recordId = $record['id'];
        $recordType = $record['type'];
        echo "<li data-id=$recordId class='ui-state-default'><span class='ui-icon ui-icon-arrowthick-2-n-s'></span>$recordType</li>";
      }

echo '
  </ul>
  <br>
  <button id="save-reorder"">Save</button>

<p>&nbsp;</p>

<a href="index.php">Admin Main Menu</a><br>
<a href="logout.php">Logout</a>

</div>
</body>
</html>
';

?>