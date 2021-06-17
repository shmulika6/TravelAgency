<?php
require_once 'dbfuncsAgency.php';
session_start();
if (!isset($_SESSION['usr_num'])) {
    header("Location: login.php");
}
if(!isset($_GET['journeyNum']))
{
    header("Location: tripsForClient.php");
}
$total="";
$people = "";
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "GET") 
    {
       $trip_id = $_GET['journeyNum'];
       $trip = getTrip($trip_id); 
       $name = $trip['journeyName'];
       $price = $trip['journeyPrice'];
       $description = $trip['journeyDescription'];
       $kosher = $trip['journeyKosher'];
       $audiancecode = $trip['journeyAudiancesCode'];
       $days = $trip['journeyDuration'];
       $startingDate = $trip['journeyStartDate'];
    }

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $people = trim(filter_input(INPUT_POST, "people", FILTER_SANITIZE_SPECIAL_CHARS));
    $trip_id = $_GET['journeyNum'];
       $trip = getTrip($trip_id); 
       $name = $trip['journeyName'];
       $price = $trip['journeyPrice'];
       $description = $trip['journeyDescription'];
       $kosher = $trip['journeyKosher'];
       $audiancecode = $trip['journeyAudiancesCode'];
       $days = $trip['journeyDuration'];
       $startingDate = $trip['journeyStartDate'];
       
    if($people=="")
    {
        $error = "יש להזין כמות הזמנות";
    }
    else if($people==0)
    {
        $error = "יש להזין כמות הזמנות חוקית";
    }
    else if(mb_strlen($people)>11)
    {
        $error = "יש להזין כמות הזמנות חוקית";
    }
    
    if($error==="")
    {
        if(signToTrip($_SESSION['usr_num'], $trip_id, $people)!=0)
        {
            $total = $people * $price;
        }
    }
}
?>
<!DOCTYPE html>
<html dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>הרשמה לטיול</title>
        <style>
        .scrollabletextbox {
         height:100px;
         width:200px;
         font-family: Arial;
         font-size: 82%;
         overflow:scroll;
         }        
        </style>
    </head>
    <body>
        <?php require_once 'menu.php';
        ?>
        
        <h1 style="text-align: center;" >הרשמה לטיול</h1>
        <form id="signtotrip" method="POST" style="text-align: center;">
            <fieldset>
                <legend>הרשמה לטיול</legend>
                <input type="hidden" name="journeyNum" 
                       value="<?= $trip_id ?>" />

                <label>שם הטיול:</label>
                <input disabled="" type="text" name="name" value="<?= $name ?>"/>
                <br><br>
                
                <label>תיאור מלא של הטיול:</label>
                <textarea disabled="" class="scrollabletextbox"> <?= $description ?>
                </textarea>    
                <br><br>

                <label>תאריך התחלה של הטיול:</label>
                <input disabled="" type="text" name="date" value="<?= date("d-m-Y", strtotime($startingDate)) ?>"/>
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
                
                <label>אנא בחר כמות הזמנות:<input type="text" name="people" value="<?= $people ?>"/> </label>
                <div style="color: red;"><?= $error ?></div>
                <br/>
                <input type="submit" value="מעוניין להירשם" /> 
                </fieldset>
            </form>
        <?php if($total!=""):?>
        <div style="text-align: center;">
            
            <p> <label> <b>המחיר לתשלום : </b> </label> <b> <?=$total?> ש"ח
                <br>
                <a href="clientsTrips.php"> הקלק על האמור לעיל כדי לעבור להצגת רשימת כל הטיולים שהזמנת</a>
                </b> </p>
       </div>
             <?php endif;?>
    </body>
</html>
