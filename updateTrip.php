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

if(!isset($_GET['journeyNum']))
{
    header("Location: unlistedTrips.php");
}

$errors = array("name" => "", "desc" => "", "date" => "", "duration" => "", "price" => "", "kosher" => "", "audiance" => "");
$err="";

$unlisted= unListedTrips();
$flag3=false;

if ($_SERVER["REQUEST_METHOD"] == "GET") 
    {
       $trip_id = $_GET['journeyNum'];
       foreach ($unlisted as $trip)
            {
                if($trip['journeyNum']==$trip_id){
                    $flag3=true;
                    break;
                }
            }
       if(!$flag3){
           header("Location: unlistedTrips.php");
       }
           
       $trip = getTrip($trip_id); 
       $name = $trip['journeyName'];
       $price = $trip['journeyPrice'];
       $description = $trip['journeyDescription'];
       $kosher = $trip['journeyKosher'];
       $audiancecode = $trip['journeyAudiancesCode'];
       $days = $trip['journeyDuration'];
       $date = $trip['journeyStartDate'];
    }
    
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $trip_id=trim(filter_input(INPUT_POST, "triptid", FILTER_SANITIZE_SPECIAL_CHARS));
    $name = trim(filter_input(INPUT_POST, "tname", FILTER_SANITIZE_SPECIAL_CHARS));
    $description = trim(filter_input(INPUT_POST, "tdesc", FILTER_SANITIZE_SPECIAL_CHARS));
    $date = trim(filter_input(INPUT_POST, "tdate", FILTER_SANITIZE_SPECIAL_CHARS));
    $days = trim(filter_input(INPUT_POST, "tdur", FILTER_SANITIZE_SPECIAL_CHARS));
    $price = trim(filter_input(INPUT_POST, "tprice", FILTER_SANITIZE_SPECIAL_CHARS));
    $kosher = trim(filter_input(INPUT_POST, "tkosher", FILTER_SANITIZE_SPECIAL_CHARS));
    $audiancecode = $_POST["audiance"];
    
    $crntdt = new DateTime();
    $tripdate = new DateTime($date);
    $flag=true;
    $flag2=false;
    
    if($name == "" || $description=="" || $days == "" || $date == "" || $price == "" || $kosher == "" || $_POST["audiance"]== "0")
    {
        $err = "אחד או יותר מהפרטים לא הוזן";
        $flag = false;
    }
    else
    {
        if(mb_strlen($name)> 128 || mb_strlen($name)< 2 )
        {
            $errors["name"]="שם הטיול לא הוזן באורך המתאים";
            $flag=false;
        }
        if(mb_strlen($description)> 1024 || mb_strlen($description) < 2)
        {
            $errors["desc"]="תיאור הטיול לא הוזן באורך המתאים";
            $flag = false;
        }
        if($days < 1 || mb_strlen($days)>11)
        {
            $errors["duration"]="משך הטיול הוזן באופן שגוי";
            $flag = false;
        }
        if($tripdate<$crntdt)
        {
            $errors["date"]="הוזן תאריך שגוי";
            $flag = false;
        }
        if($price<1 || mb_strlen($price) > 11)
        {
            $errors["price"]="מחיר הטיול הוזן באופן שגוי";
            $flag = false;
        }
        if($kosher=="ל" || $kosher == "כ")
        {
            
        }
        else
        {
            $errors["kosher"]="הוזן ערך שגוי בשדה הכשרות";
            $flag = false;
        }
        
        if($flag)
        {
            foreach ($unlisted as $trip)
            {
                if($trip['journeyNum']==$trip_id)
                {
                    $flag2=true;
                    break;
                }
            }
            
            if(updtTripByNum($trip_id,$name, $description, $tripdate->format('Y-m-d'), $days, $price, $kosher, $audiancecode)!=0 && $flag2)
            {
                $err="עדכון פרטי הטיול עברו בהצלחה";
            }
            else
                header("Location: unlistedTrips.php");
        }
    }
}
?>





<!DOCTYPE html>
<html  dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>עדכון טיול</title>
        <link rel="stylesheet" href="site.css"/>
    </head>
    <body>
        <?php require_once 'menu.php';
        ?>
        <form method="post">
            <input type="hidden" name="triptid" 
                       value="<?= $trip_id ?>" />
            <fieldset>
                <legend>עדכון טיול</legend>
                <label>שם הטיול<input required type="text" name="tname" minlength="2" maxlength="128" value="<?= $name ?>"/> </label>
                <div style="color: red;"><?= $errors["name"] ?></div>
                <br/>
                <label>תיאור המסלול<input required type="text" name="tdesc" minlength="2" maxlength="1024" value="<?= $description ?>"/> </label>
                <div style="color: red;"><?= $errors["desc"] ?></div>
                <br/>
                <label>תאריך התחלה<input required type="date" name="tdate" value="<?= $date ?>"/> </label>
                <div style="color: red;"><?= $errors["date"] ?></div>
                <br/>
                <label>משך הטיול<input type="text" name="tdur" value="<?= $days ?>"/> </label>
                <div style="color: red;"><?= $errors["duration"] ?></div>
                <br/>
                <label>מחיר ליחיד<input type="text" name="tprice" value="<?= $price ?>"/> </label>
                <div style="color: red;"><?= $errors["price"] ?></div>
                <br/>
                <label>האם הטיול כשר?<input type="text" name="tkosher" value="<?= $kosher ?>"/> </label>
                <div style="color: red;"><?= $errors["kosher"] ?></div>
                <br/>
                <label>קהל היעד
                    <select name="audiance">
                    <option value="0" <?php if($audiancecode == "0") echo 'selected="selected"';?> > <?= $audiance["0"] ?></option>
                    <option value="1" <?php if($audiancecode == "1") echo 'selected="selected"';?> > <?= $audiance["1"] ?></option>
                    <option value="2" <?php if($audiancecode == "2") echo 'selected="selected"';?> > <?= $audiance["2"] ?></option>
                    <option value="3" <?php if($audiancecode == "3") echo 'selected="selected"';?> > <?= $audiance["3"] ?></option>
                    <option value="4" <?php if($audiancecode == "4") echo 'selected="selected"';?> > <?= $audiance["4"] ?></option>
                    <option value="5" <?php if($audiancecode == "5") echo 'selected="selected"';?> > <?= $audiance["5"] ?></option>
                    <option value="6" <?php if($audiancecode == "6") echo 'selected="selected"';?> > <?= $audiance["6"] ?></option>
	           </select>
                </label>         
                <br>
                <div style="color: red; text-align:center"><?= $err ?></div>
                <input type="submit" value="שמור"/>
            </fieldset>
        </form>
        <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
        <?php if($err=="עדכון פרטי הטיול עברו בהצלחה"):?>
        <div style="text-align: center;">
            
            <p> <b>
                    <a href="unlistedTrips.php"> נא להקליק כאן להמשך העבודה</a>
                </b> </p>
       </div>
        <?php endif;?>
        <br/>
        
    </body>
</html>
