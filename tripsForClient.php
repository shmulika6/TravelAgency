<?php
require_once 'dbfuncsAgency.php';
session_start();
if (!isset($_SESSION['usr_num'])) {
    header("Location: login.php");
}

$search_text = '';

if ($_SERVER["REQUEST_METHOD"] == "GET") 
    {
        if (isset($_GET['search'])) {
            $search_text = $_GET['search'];
        }
    }

$trips = tripsFilter($search_text,$_SESSION['usr_num']);
?>





<!DOCTYPE html>


<html dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title> טיולים שטרם יצאו</title>
        <link rel="stylesheet" href="site.css"/>
    </head>
    <body>
        <?php require_once 'menu.php';
        ?>
        <form method="GET">
            <fieldset>
            <legend>טיולים שטרם יצאו</legend>
            <div style="text-align: center;">
            <label >חפש לפי תיאור הטיול:</label>
                    <input type="text" name="search" value="<?= $search_text; ?>">
                    <input type="submit" value="חפש"> <br>&nbsp;
             </div>
            
            <table border="1" style="text-align: right; margin-left: auto; margin-right: auto;">
                <caption>טיולים שטרם יצאו</caption>
                <tr>
                    <th>שם טיול </th><th>תאריך התחלה </th><th>משך טיול בימים </th><th>מחיר ליחיד בש"ח</th><th>כשר </th><th> קהל יעד</th><th> הרשמה</th>
                </tr>  
                <?php foreach ($trips as $shora) : ?>
                    <tr>
                        <td><?= $shora['journeyName'] ?></td>
                        <td><?= date("d-m-Y", strtotime($shora['journeyStartDate'])) ?> </td>
                        <td><?= $shora['journeyDuration'] ?> </td>
                        <td><?= $shora['journeyPrice'] ?> </td>
                        <td><?php if ($shora['journeyKosher']=="ל"):
                                      echo 'לא';
                                  else: echo 'כן'; 
                                  endif;?>   
                        </td>
                        <td><?= $audiance[$shora['journeyAudiancesCode']] ?> </td>
                        
                        <td>
                       <button>
                          <a href="./signTrip.php?journeyNum=<?= $shora["journeyNum"] ?>">הירשם</a>
                       </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
           </fieldset>
        </form>
    </body>
</html>
