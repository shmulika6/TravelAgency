<?php
require_once 'dbfuncsAgency.php';

session_start();
if (!isset($_SESSION['usr_num'])) {
    header("Location: login.php");
}
if(!isset($_GET['orderNum']))
{
    header("Location: tripsForClient.php");
}
$total="";
$error="";
if ($_SERVER["REQUEST_METHOD"] == "GET") 
    {
       $order_id = $_GET['orderNum'];
       $order_details= getOrder($order_id);
       $journeynum=$order_details["orderJournyNum"];
       $quantity=$order_details["orderQuantity"];
       $orderDate=$order_details["orederDate"];
       
       $trip_details=getTripByOrder($order_id);
       $name = $trip_details['journeyName'];
       $price = $trip_details['journeyPrice'];
       $description = $trip_details['journeyDescription'];
       $kosher = $trip_details['journeyKosher'];
       $audiancecode = $trip_details['journeyAudiancesCode'];
       $days = $trip_details['journeyDuration'];
       $date = $trip_details['journeyStartDate'];
    }
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
       $order_id = $_GET['orderNum'];
       $order_details= getOrder($order_id);
       $journeynum=$order_details["orderJournyNum"];
       $orderDate=$order_details["orederDate"];
       
       $trip_details=getTripByOrder($order_id);
       $name = $trip_details['journeyName'];
       $price = $trip_details['journeyPrice'];
       $description = $trip_details['journeyDescription'];
       $kosher = $trip_details['journeyKosher'];
       $audiancecode = $trip_details['journeyAudiancesCode'];
       $days = $trip_details['journeyDuration'];
       $date = $trip_details['journeyStartDate'];
       
       $quantity = trim(filter_input(INPUT_POST, "updateQuan", FILTER_SANITIZE_SPECIAL_CHARS));
        
    if($quantity=="")
    {
        $error = "יש להזין כמות הזמנות";
    }
    else if($quantity==0)
    {
        $error = "יש להזין כמות הזמנות חוקית";
    }
    else if(mb_strlen($quantity)>11)
    {
        $error = "יש להזין כמות הזמנות חוקית";
    }
    
    if($error==="")
    {
        if(updtorder($order_id, $quantity)!=0)
        {
            $total = $quantity * $price;
        }
    }
}

?>



<!DOCTYPE html>
<html  dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>עדכון הזמנה</title>
        <link rel="stylesheet" href="site.css"/>
    </head>
    <body>
        <?php require_once 'menu.php';
        ?>
        <form method="post">
            <input type="hidden" name="orderNum" 
                       value="<?= $order_id ?>" />
            <fieldset>
                <legend>עדכון הזמנה</legend>
               <label>שם הטיול:</label>
                <input disabled="" type="text" name="name" value="<?= $name ?>"/>
                <br><br>
                
                <label>תיאור מלא של הטיול:</label>
                <textarea name="desc" disabled="" class="scrollabletextbox"> <?= $description ?>
                </textarea>    
                <br><br>

                <label>תאריך התחלה של הטיול:</label>
                <input disabled="" type="text" name="date" value="<?= date("d-m-Y", strtotime($date)) ?>"/>
                <br><br>
                
                <label>משך הטיול בימים:</label>
                <input disabled="" type="text" name="dura" value="<?= $days ?>"/>
                <br><br>
                
                <label>מחיר ליחיד בש"ח:</label>
                <input disabled="" type="text" name="price" value="<?= $price ?>"/>
                <br><br>
                
                <label>כשר? :</label>
                <input disabled="" type="text" name="kosher" 
                 value="<?php if ($kosher=='ל'):
                                      echo 'לא';
                                  else: echo 'כן'; 
                                  endif; ?>" />
                <br><br>
                
                <label>קהל היעד:</label>
                <input disabled="" type="text" name="audiance" value="<?= $audiance[$audiancecode] ?>"/>
                <br><br>
                
                <label>מספר הזמנה:</label>
                <input disabled="" type="text" name="orderNum" value="<?= $order_id ?>"/>
                <br><br>
                
                <label>כמות מוזמנים מקורית:</label>
                <input disabled="" type="text" name="quantity" value="<?= $quantity ?>"/>
                <br><br>
                
                <label>סכום מקורי לתשלום בש"ח:</label>
                <input disabled="" type="text" name="totalprice" value="<?= $quantity * $price ?>"/>
                <br><br>
                
                <label>הקלד כאן את כמות המוזמנים המעודכנת:</label>
                <input type="text" name="updateQuan" value="<?= $quantity?>"/>
                 <div style="color: red;"><?= $error ?></div>
                <br><br>
                
                <input type="submit" value="שמור"/>
            </fieldset>
        </form>
        
        <?php if($total!=""):?>
        <div style="bottom: -2%; text-align: center; position: fixed; width: 100%;">
            
            <p> <label> <b>המחיר המעודכן לתשלום : </b> </label> <b> <?=$total?> ש"ח
                <br>
                <a href="clientsTrips.php"> הקלק על האמור לעיל כדי לעבור להצגת רשימת כל הטיולים שהזמנת</a>
                </b> </p>
       </div>
             <?php endif;?>
        <br/>
        
    </body>
</html>
