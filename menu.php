<?php 
require_once 'dbfuncsAgency.php';



?>
<div  style="border-bottom: Blue 2px solid; padding-bottom: 20px">
    <p><span style='float:left' >המשתמש המחובר:<?= $_SESSION['usr_rlnm'] ?></span>
        <span style='float:right' >
            <a class=mnuitm href=changePwd.php >שינוי סיסמא </a>&nbsp;
            <?php if(isAdmin($_SESSION['usr_admin'])):?>
            <a class=mnuitm href=addTrip.php >הוספת טיול חדש</a>&nbsp;
            <a class=mnuitm href=unlistedTrips.php >טיולים שטרם יצאו וטרם נרשמו אליהם</a>&nbsp;
            <?php else:?>
            <a class=mnuitm href=tripsForClient.php>טיולים שטרם יצאו</a>&nbsp;
            <a class=mnuitm href=clientsTrips.php >טיולים שנרשמת אליהם</a>&nbsp;
            <?php endif;?>
            <a class=mnuitm href=disconnect.php >התנתק</a>
        </span>
    </P>
</div>