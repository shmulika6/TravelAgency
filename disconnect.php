<?php
$err="";
session_start();
foreach ($_SESSION as $ky => $vlu) {
    unset($_SESSION[$ky]);
}
echo 'מתבצע ניתוק...';
header('Refresh:2 ; URL=login.php');

