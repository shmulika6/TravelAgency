<?php
require_once 'dbfuncsAgency.php';
$uid = "";
$pwd = "";
$err = "";
session_start();
if (isset($_SESSION['usr_num'])) {
    if(isAdmin($_SESSION['usr_num']))
        header("Location: unlistedTrips.php");
    else
        header("Location: tripsForClient.php");
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $uid = trim(filter_input(INPUT_POST, 'uid', FILTER_SANITIZE_SPECIAL_CHARS));
    $pwd = trim(filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_SPECIAL_CHARS));
    $usrdtls = getUserCredentials($uid, $pwd);
    if ($usrdtls) {
        $_SESSION['usr_uid'] = $uid;
        $_SESSION['usr_num'] = $usrdtls['userNum'];
        $_SESSION['usr_rlnm'] = $usrdtls['userRealname'];
        $_SESSION['usr_admin'] = $usrdtls['userType'];
        
        if(isAdmin($_SESSION['usr_admin']))
        {
            $err="מתבצעת כניסה...";
            header('Refresh:2 ; URL=unlistedTrips.php');
        }
        else{
            $err="מתבצעת כניסה...";
            header('Refresh:2 ; URL=tripsForClient.php');
        }
        
    } else {
        $err = "מזהה  משתמש לא מוכר או לא פעיל או הצירוף של שם המשתמש והסיסמא שגוי";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>התחברות</title>
        <link rel="stylesheet" href="site.css"/>
    </head>
    <body dir="rtl">
        <form method="post">
            <fieldset>
                <legend>התחברות</legend>
                <label>מזהה/ מייל משתמש<input required type="email" name="uid" size=40 value="<?= $uid ?>"/> </label>
                <br/>
                <label>סיסמא<input required type="password" name="pwd"/> </label>
                <br/>
                <p class="adom"><?= $err ?> </p>
                <input style="width: 130px" type="submit" value="כנס"/>
                <br><br>
                <button style="width: 205px" onclick="window.location.href='addUser.php'">לקוח חדש</button>
                <input style="width: 205px" type="reset" value="נקה">
            </fieldset>
        </form>
        <br/>
        
    </body>
</html>