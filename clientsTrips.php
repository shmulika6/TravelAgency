<?php
require_once 'dbfuncsAgency.php';
session_start();
if (!isset($_SESSION['usr_num'])) {
    header("Location: login.php");
}


/*
 * Function to find the difference  between today to another date......[in days]
 */
function dateDiff($date2) 
	{
          $date1= date("Y-m-d");
	  $date1_ts = strtotime($date1);
	  $date2_ts = strtotime($date2);
	  $diff = $date2_ts - $date1_ts;
	  return round($diff / (60*60*24));
	}
$msg="";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $ordernum = $_POST['dltorder'];
    if (dltOrderByNum($ordernum) != 0) {
        $msg = "מחיקה הצליחה";
    } else {
        $msg = "מחיקה נכשלה";
    }
}
$orders = getClientsTrips($_SESSION['usr_num']);


?>


<!DOCTYPE html>
<html dir="rtl" >
    <head>
        <meta charset="UTF-8">
         <link rel="stylesheet" href="site.css"/>
    </head>
    <body>
         <?php require_once 'menu.php';?>
        <br>
        <?php if(empty($orders)):?>
        <p class="adom"><?php echo " לא נמצאו טיולים שנרשמת אליהם " ?> </p>
        <?php else: ?>
        <form method="POST" onsubmit="return confirm('האם אתה בטוח שברצונך למחוק הזמנה?');">
            <fieldset>
                <legend>הזמנות לקוח</legend>
        <table border="1" style="text-align: right; margin-left: auto; margin-right: auto;">
            <caption>הזמנות לקוח</caption>
                <tr>
                    <th>מספר טיול</th><th>שם טיול</th><th>כמות בהזמנה </th><th>סה"כ לתשלום </th><th>עריכת הזמנה </th><th>אופציה לביטול</th>
                </tr>  
                <?php foreach ($orders as $shora): ?>
                    <tr>
                        <td><?= $shora['orderJournyNum'] ?></td>
                        <td><?= $shora['journeyName'] ?></td>
                        <td><?= $shora['orderQuantity'] ?> </td>
                        <td><?= $shora['journeyPrice'] * $shora['orderQuantity']. ' ש"ח' ?> </td>
                        <td>
                       <button>
                          <a href="./updateOrder.php?orderNum=<?= $shora["orderNum"] ?>">ערוך הזמנה</a>
                       </button>
                        </td>
                        <?php if(dateDiff($shora['journeyStartDate'])>10):  ?>
                        <td><button style="margin: auto; display: block;" type=submit name=dltorder value="<?= $shora['orderNum'] ?>" >מחק</button></td>
                              <?php endif;?>
                      
                    </tr>
                <?php endforeach; ?>
            </table>
           </fieldset>
        </form>
        <?php endif;?>
        <div style="color: red; bottom: 30%; text-align: center; position: fixed; width: 100%;"><?= $msg ?> </div>
    </body>
</html>
