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
    <title>
    <?php 
    
    if(isset($_GET['database']) && isset($_GET['table'])) {
        echo $_GET['database']." ⮞ ".$_GET['table']." - ";
    } else if(isset($_GET['database']) && !isset($_GET['table'])) {
        echo $_GET['database']." - ";
    }
    echo $brand_name;
    ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="./style.css" rel="stylesheet"> 
    <link rel="apple-touch-icon" sizes="57x57" href="./favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="./favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="./favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="./favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="./favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="./favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="./favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="./favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="./favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="./favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
    <link rel="manifest" href="./favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="./favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

</head>
<body>

<?php if(isset($_GET['logout'])) { 
    session_unset();
    header("Location: ./?login");
} else if(isset($_GET['login'])) { ?>
    <main>
        <div class="wrapper-card">
            <h2 style="font-weight: 900;">Log In</h2>
            <h3>Please Log In to view the databases!</h3>
            <form action="index.php" method="post">
                <div class="text-field text-field--outlined full-width" style="max-width: 90%; left: 5%;">
                    <input type="text" id="username-field" name="user" class="text-field__input" required>
                    <div class="notched-outline notched-outline--upgraded notched-outline--notched">
                        <div class="notched-outline__leading"></div>
                        <div class="notched-outline__notch" style="width: 64px;">
                            <label class="floating-label floating-label--float-above" for="my-text-field">Username</label>
                        </div>
                        <div class="notched-outline__trailing"></div>
                    </div>
                </div>  
                <div class="text-field text-field--outlined full-width" style="max-width: 90%; left: 5%;">
                    <input type="password" id="password-field" name="pass" class="text-field__input" required>
                    <div class="notched-outline notched-outline--upgraded notched-outline--notched">
                        <div class="notched-outline__leading"></div>
                        <div class="notched-outline__notch" style="width: 62.25px;">
                            <label class="floating-label floating-label--float-above" for="my-text-field">Password</label>
                        </div>
                        <div class="notched-outline__trailing"></div>
                    </div>
                </div> 
                <input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1" />
            </form>       
            <div class="footer-wrapper" style="position: fixed; right: 5%; bottom: 5%;">
            </div>
         </div>
    </main>
<?php } else {
    echo '<a class="note" href="./?logout" title="Logout"><span class="material-icons">exit_to_app</span></a><a class="note" onclick="change()" title="Toggle Theme (light/dark)"><span class="material-icons">brightness_6</span></a>';
    js();
    if(isset($_POST['user']) && isset($_POST['pass'])) {
        try {
          $conn = new PDO("mysql:host=$server_name", $_POST['user'], $_POST['pass']);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $_SESSION["user"] = $_POST['user'];
          $_SESSION["pass"] = $_POST['pass'];
          header("Location: ./");
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
          session_unset();
          header("Location: ./?login");
        }
    } else {
        if(isset($_SESSION["user"]) && isset($_SESSION["pass"])) {
            try {
              $conn = new PDO("mysql:host=$server_name", $_SESSION['user'], $_SESSION['pass']);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              data($conn);
            } catch(PDOException $e) {
              echo "Connection failed: " . $e->getMessage();
              session_unset();
              header("Location: ./?login");
            }
        } else {
            header("Location: ./?login");
        }
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
<?php
 function js() {
    echo "<script>if(localStorage.getItem('dark__') == \"true\") {
        document.body.classList.add('dark');
    } else {
        document.body.classList.remove('dark');
    }</script>";
 }



?>


function change() {
    document.body.classList.toggle('dark');
        if(localStorage.getItem('dark__') == "true") {
            localStorage.setItem('dark__', 'false');
        } else {
            localStorage.setItem('dark__', 'true');
        }
}

function intable(element) {
    <?php if(isset($_GET['database'])) {echo 'window.location = "./?database='.$_GET['database'].'&table="+element';} ?>
}
function table(element) {
    window.location = "./?database="+element;
}
</script>
</body>
</html>