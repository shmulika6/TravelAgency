<?php
require_once 'dbfuncsAgency.php';

session_start();
if (!isset($_SESSION['usr_num'])) {
    header("Location: login.php");
}
if(isAdmin($_SESSION["usr_admin"])==false)
{
    header("Location: tripsForClient.php");
}
$msg="";
$unlistedtrips= unListedTrips();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $tripnum = $_POST['dlttrip'];
    $unlistedtrips= unListedTrips();

    if (dlttripByNum($tripnum) != 0) {
        $msg="מחיקה הצליחה!";
        header('Refresh:2 ; URL=unlistedTrips.php');
    } else {
        $msg = "מחיקה נכשלה, לקוח הזמין כרטיסים לטיול בזמן המחיקה";
    }
}





?>

<!DOCTYPE html>
<html dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title> טיולים שטרם יצאו וטרם נרשמו אליהם</title>
        <link rel="stylesheet" href="site.css"/>
    </head>
    <body>
        <?php require_once 'menu.php';
        ?>
        <form method="POST" onsubmit="return confirm('האם אתה בטוח שברצונך למחוק טיול?');">
            <fieldset>
                <legend>טיולים שטרם יצאו וטרם נרשמו אליהם</legend>
            <div style="text-align: center;">
                
            <table border="1" style="text-align: right; margin-left: auto; margin-right: auto;">
                <caption>טיולים שטרם נרשמו אליהם</caption>
                <tr>
                    <th>שם טיול </th><th>תאריך התחלה </th><th>משך טיול בימים </th><th>מחיר ליחיד בש"ח</th><th>כשר </th><th> קהל יעד</th><th> מחיקה</th><th> עריכה</th>
                </tr>  
                <?php foreach ($unlistedtrips as $shora) : ?>
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
                        <button style="margin: auto; display: block;" type=submit name=dlttrip value="<?= $shora['journeyNum'] ?>" >מחק</button>
                        </td>

                        
                        <td>
                       <button>
                          <a href="./updateTrip.php?journeyNum=<?= $shora["journeyNum"] ?>">ערוך</a>
                       </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            </div>
            </fieldset>
        </form>
        
        <div style="color: red; bottom: 30%; text-align: center; position: fixed; width: 100%;"><?= $msg ?> </div>
    </body>
</html>
