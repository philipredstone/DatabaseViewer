<?php
if (!empty($_SERVER['HTTPS'])) {
    ini_set("session.cookie_secure", 1);
}
session_start();
include("config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($_GET['database'])) {
        echo $_GET['database']." - ";
    } ?>Database Viewer</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
    <link href="./style.css" rel="stylesheet"> 

</head>
<body>
    <a class="logout" href="./logout.php">logout</a>

<?php

if(isset($_POST['user']) && isset($_POST['pass'])) {
    try {
      $conn = new PDO("mysql:host=$servername", $_POST['user'], $_POST['pass']);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $_SESSION["user"] = $_POST['user'];
      $_SESSION["pass"] = $_POST['pass'];
      header("Location: ./");
    } catch(PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
      session_unset();
      header("Location: ./login.php");
    }
} else {
    if(isset($_SESSION["user"]) && isset($_SESSION["pass"])) {
        try {
          $conn = new PDO("mysql:host=$servername", $_SESSION['user'], $_SESSION['pass']);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          data($conn);
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
          session_unset();
          header("Location: ./login.php");
        }
    } else {
        header("Location: ./login.php");
    }
}


function data($conn) {
    if(!isset($_GET['database']) && !isset($_GET['table'])) {
        $query = $conn->prepare('SHOW DATABASES');
        $query->execute();
        echo '<div class="table"  style="margin-top: 1%; margin-left:1%; margin-bottom:50px; width: 98%;"> <table class="table__table"> <thead> <tr class="table__header-row"> <th class="table__header-cell" role="columnheader" scope="col" style="font-weight: bold;">Databases</th> <th class="table__header-cell" role="columnheader" scope="col" style="font-weight: bold; text-align: right;">N° of tables</th> </tr></thead> <tbody  class="table__content">';
        while($rows = $query->fetch(PDO::FETCH_ASSOC)){
            if($rows["Database"] != "information_schema") {
                echo '<tr style="cursor:pointer;" onclick="table(\''.$rows["Database"].'\')"  data-row-id="u3" class="table__row" aria-selected="false"><td class="table__cell" id="u5">'.$rows["Database"].'</td>';
                $query2 = $conn->prepare('SELECT count(*) AS TOTALNUMBEROFTABLES FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = "'.$rows["Database"].'"');
                $query2->execute();
                while($rows = $query2->fetch(PDO::FETCH_ASSOC)){
                    echo '<td class="table__cell table__cell--numeric">'.$rows["TOTALNUMBEROFTABLES"].'</td>';
                }
            }
        }
        echo '</tbody></table></div>';
    } else if(isset($_GET['database']) && !isset($_GET['table'])) {
        if($_GET['database'] != "information_schema") {
            $db = $_GET['database'];
            $query = $conn->prepare('SHOW tables FROM '.$db);
            $query->execute();
            echo '<div class="table" style="margin-top: 1%; margin-left:1%; margin-bottom:50px; width: 98%;"><table class="table__table"><thead><tr class="table__header-row"><th class="table__header-cell" role="columnheader" scope="col" style="font-weight: bold;">Tables</th></tr></thead><tbody class="table__content">';
                        while($rows = $query->fetch(PDO::FETCH_ASSOC))
                            echo '<tr onclick="intable(\''.$rows['Tables_in_'.$db].'\')" style="cursor:pointer;" data-row-id="u3" class="table__row" aria-selected="false"><td class="table__cell" id="u5">'.$rows['Tables_in_'.$db].'</td>';
            echo '</tbody></table></div>';
        }
    } else if(isset($_GET['database']) && isset($_GET['table'])) {
        if($_GET['database'] != "information_schema") {
            $query = $conn->prepare('SELECT * FROM '.$_GET['database'].'.'.$_GET['table']);
            $query->execute();
            $header = true;
            while($rows = $query->fetch(PDO::FETCH_ASSOC)) {
                if($header) {
                    echo '<div class="table" style="margin-top: 1%; margin-left:1%; margin-bottom:50px; width: 98%;"><table class="table__table"><thead><tr class="table__header-row">';
                    foreach ($rows as $key => $value){
                        echo '<th class="table__header-cell" role="columnheader" scope="col" style="font-weight: bold;">'.$key.'</th>';
                    }
                    $header = false;
                    echo '</tr></thead><tbody class="table__content">';
                }
                echo '<tr data-row-id="u3" class="table__row" aria-selected="false">';
                foreach ($rows as $key => $value){
                    echo '<td class="table__cell" id="u3" >'.$value.'</td>';
                }
                echo '</tr>';
            }
            echo '</tbody></table></div>';
        }
    }
}


?>

<br />
<script>

function intable(element) {
    <?php if(isset($_GET['database'])) {
        echo 'window.location = "./?database='.$_GET['database'].'&table="+element';
    } 
    ?>
}
function table(element) {
    window.location = "./?database="+element;
}
</script>
</body>

</html>
