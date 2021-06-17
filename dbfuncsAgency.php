<?php

$audiance=array("0"=>"יש לבחור קהל יעד", "1"=>"מיטיבי לכת","2"=>"משפחות עם ילדים","3"=>"מתאים לכולם"
    ,"4"=>"פנסיונרים","5"=>"מחפשים חברה","6"=>"אחרי צבא");


$dsn = 'mysql:host=localhost;dbname=travelagencydb';
$username = 'root';
$password = '';
try {
    $db = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
    $db->exec("set NAMES utf8");
    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "db connection open failed due: " . $e->getMessage();
    exit();
}

function isAdmin($usertype){
    if($usertype==='מ'){
        return true;
    }
    return false;
}

/* Get the users details to check if signed-in successfully (login.php use) */
function getUserCredentials($uid, $pwd) {
    global $db;
    $query_text = "SELECT userNum,userRealname,userType "
            . "FROM tblusers "
            . "WHERE userEmail=:uid and userPassword=:pwd";
    try {
        $cmd = $db->prepare($query_text);
        $cmd->bindValue(':uid', $uid);
        $cmd->bindValue(':pwd', $pwd);
        $cmd->execute();
        $result = $cmd->fetch();
        return $result;
    } catch (PDOException $ex) {
        echo "getUserCredentials failed due: " . $ex->getMessage();
        exit();
    }
}




/* Add new User to DB (addUser.php use) */
function addUser($eml, $pwd, $rlnm) {
    global $db;
    $query_text = "insert into tblusers (userEmail,userPassword,userRealname,userType) "
            . "values (:email,:upwd,:urlnm,'ל')";
    try {
        $cmd = $db->prepare($query_text);
        $cmd->bindValue(':email', $eml);
        $cmd->bindValue(':upwd', $pwd);
        $cmd->bindValue(':urlnm', $rlnm);
        
        
        $cmd->execute();
        $rowcount = $cmd->rowCount();
        If ($rowcount == 0) {
            return 0;
        } else {
            return $db->lastInsertId();
        }
    } catch (PDOException $ex) {
        echo "addUser  failed, due: " . $ex->getMessage();
        exit();
    }
}

/* Get all user details by his number in table*/
function getUsrDtlsByNum($usrnum)
{
    global $db;
    $query_text = "SELECT *  "
            . "FROM tblusers "
            . "WHERE userNum=:unum";
    try {
        $cmd = $db->prepare($query_text);
        $cmd->bindValue(':unum', $usrnum);
        $cmd->execute();
        $result = $cmd->fetch();
        return $result;
    } catch (PDOException $ex) {
        echo "getUsrDtlsByNum failed due: " . $ex->getMessage();
        exit();
    }
}






/* Updating users password */
function updtUsrByNum($usrnum, $nwpwd)
{
    global $db;
    $query_text = "UPDATE tblusers" 
                   ." SET userPassword=:nwpwd" 
                   ." WHERE userNum=:unum";
                                

    try {
        $cmd = $db->prepare($query_text);
        $cmd->bindValue(':nwpwd', $nwpwd);
        $cmd->bindValue(':unum', $usrnum);
        $cmd->execute();
        $rowcount = $cmd->rowCount();
        return $rowcount;

    } catch (PDOException $ex) {
        echo "updtUsrByNum failed, due: " . $ex->getMessage();
        exit();
    }
}


/* Add new Trip to DB (addTrip.php use) */
function addTrip($name, $desc, $date, $duration, $price, $kosher, $audiance) {
    global $db;
    $query_text = "insert into tbljourneys (journeyName,journeyDescription,journeyStartDate,"
            . "journeyDuration,journeyPrice,journeyKosher,journeyAudiancesCode) "
            . "values (:name,:desc,:date,:dur,:price,:kosher,:audiance)";
    try {
        $cmd = $db->prepare($query_text);
        $cmd->bindValue(':name', $name);
        $cmd->bindValue(':desc', $desc);
        $cmd->bindValue(':date', $date);
        $cmd->bindValue(':dur', $duration);
        $cmd->bindValue(':price', $price);
        $cmd->bindValue(':kosher', $kosher);
        $cmd->bindValue(':audiance', $audiance);
        $cmd->execute();
        $rowcount = $cmd->rowCount();
        If ($rowcount == 0) {
            return 0;
        } else {
            return $db->lastInsertId();
        }
    } catch (PDOException $ex) {
        echo "addTrip  failed, due: " . $ex->getMessage();
        exit();
    }
}



function tripsFilter($search_text = '', $client_num)
{
   global $db;
   try
   {
      if ($search_text == '') {
         $query = 'SELECT journeyNum,journeyName,journeyStartDate,journeyDuration,journeyPrice,journeyKosher,journeyAudiancesCode'
                 . ' FROM tbljourneys '
                 . 'Where CURDATE()< journeyStartDate AND'
                 . ' journeyNum NOT IN (select orderJournyNum'
                 . ' from tblorders'
                 . ' where orderUserNum = :u)';
         $statement = $db->prepare($query);
         $statement->bindValue(":u",$client_num);
      }
      
      else {
         $query = 'SELECT journeyNum,journeyName,journeyStartDate,journeyDuration,journeyPrice,'
                 . 'journeyKosher,journeyAudiancesCode FROM tbljourneys '
                 . 'WHERE journeyDescription LIKE :s AND CURDATE()< journeyStartDate AND'
                 . ' journeyNum NOT IN (select orderJournyNum'
                 . ' from tblorders'
                 . ' where orderUserNum = :u)';
         $statement = $db->prepare($query);
         $statement->bindValue(':s', '%' . $search_text . '%');
         $statement->bindValue(":u",$client_num);
      }
      $statement->execute();
      $result = $statement->fetchAll();  
      return $result;
    }
    catch (PDOException $ex) {
        echo "tripsFilter failed due: " . $ex->getMessage();
        exit();
    }
}

function getTrip($trip_id)
{
    global $db;
    try
    {
        $query = 'SELECT * FROM tbljourneys'
                . ' WHERE journeyNum = :tripid';
        $statement = $db->prepare($query);
        $statement->bindValue(':tripid',$trip_id);
        $statement->execute();
        $result = $statement->fetch();       

        return $result;
    }
    catch (PDOException $ex) {
        echo "getTrip failed due: " . $ex->getMessage();
        exit();
    }
}



function signToTrip($usernum,$tripnum,$people) {
    global $db;
    $query_text = "insert into tblorders (orderUserNum,orderJournyNum,orderQuantity,orederDate) "
            . "values (:usernum,:tripnum,:people,CURDATE())";
    try {
        $cmd = $db->prepare($query_text);
        $cmd->bindValue(':usernum', $usernum);
        $cmd->bindValue(':tripnum', $tripnum);
        $cmd->bindValue(':people', $people);
        
        $cmd->execute();
        $rowcount = $cmd->rowCount();
        If ($rowcount == 0) {
            return 0;
        } else {
            return $db->lastInsertId();
        }
    } catch (PDOException $ex) {
        echo "signToTrip failed, due: " . $ex->getMessage();
        exit();
    }
}


function getClientsTrips($usernum)
{
    global $db;
    try
    {
        $query = "SELECT a.orderNum, a.orderJournyNum, a.orderQuantity, j.journeyPrice, j.journeyStartDate, j.journeyName "
                . "FROM tblorders a INNER JOIN tbljourneys j "
                . "WHERE orderUserNum = :usernum AND a.orderJournyNum = j.journeyNum";
        $statement = $db->prepare($query);
        $statement->bindValue(':usernum',$usernum);
        $statement->execute();
        $result = $statement->fetchAll();  
        
        return $result;
    }
    catch (PDOException $ex) {
        echo "getClientsTrips failed, due: " . $ex->getMessage();
        exit();
    }
}


function dltOrderByNum($onum)
{
    global $db;
    $query_text = "delete from tblorders where orderNum=:onum";
    try {
        $cmd = $db->prepare($query_text);
        $cmd->bindValue(':onum', $onum); 
        $cmd->execute();
        $rowcount=$cmd->rowCount();
        return $rowcount;
    } catch (PDOException $ex) {
        echo "dltOrderByNum failed, due: ".$ex->getMessage();
        exit();
    }
}


function unListedTrips()
{
    global $db;
    try
    {
        $query = 'SELECT t.journeyNum,t.journeyName,t.journeyStartDate,t.journeyDuration,t.journeyPrice,t.journeyKosher,t.journeyAudiancesCode'
                . ' FROM tbljourneys as t Where CURDATE()< t.journeyStartDate AND'
                . ' t.journeyNum NOT IN (SELECT orderJournyNum from tblorders) order by t.journeyStartDate ';
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();  
        
        return $result;
    }
    catch (PDOException $ex) {
        echo "getClientsTrips failed, due: " . $ex->getMessage();
        exit();
    }
}


function dlttripByNum($tnum)
{
    global $db;
    $query_text = "delete from tbljourneys where journeyNum=:tnum and :tnum"
            . " not in (select orderJournyNum from tblorders) ";
    try {
        $cmd = $db->prepare($query_text);
        $cmd->bindValue(':tnum', $tnum); 
        $cmd->execute();
        $rowcount=$cmd->rowCount();
        return $rowcount;
    } catch (PDOException $ex) {
        echo "dlttripByNum failed, due: ".$ex->getMessage();
        exit();
    }
}



function updtTripByNum($tripnum, $tripname, $desc, $tripdate, $duration, $price, $kosher, $audiance)
{
    global $db;
    $query_text = "UPDATE tbljourneys"
            . " SET journeyName=:tname,journeyDescription=:desc,journeyStartDate=:tdate,journeyDuration=:tduration,"
            . " journeyPrice=:price,journeyKosher=:kosher,journeyAudiancesCode=:audiance"
            . " WHERE journeyNum=:tnum and :tnum"
            . " not in (select orderJournyNum from tblorders)";
                                

    try {
        $cmd = $db->prepare($query_text);
        $cmd->bindValue(':tnum', $tripnum);
        $cmd->bindValue(':tname', $tripname);
        $cmd->bindValue(':desc', $desc);
        $cmd->bindValue(':tdate', $tripdate);
        $cmd->bindValue(':tduration', $duration);
        $cmd->bindValue(':price', $price);
        $cmd->bindValue(':kosher', $kosher);
        $cmd->bindValue(':audiance', $audiance);
        $cmd->execute();
        $rowcount = $cmd->rowCount();
        return $rowcount;

    } catch (PDOException $ex) {
        echo "updtTripByNum failed, due: " . $ex->getMessage();
        exit();
    }
}


function getTripByOrder($order_id)
{
    global $db;
    try
    {
        $query = 'SELECT * FROM tbljourneys'
                . ' WHERE journeyNum = (select orderJournyNum from tblorders'
                . ' where orderNum=:o)';
        $statement = $db->prepare($query);
        $statement->bindValue(':o',$order_id);
        $statement->execute();
        $result = $statement->fetch();       

        return $result;
    }
    catch (PDOException $ex) {
        echo "getTripByOrder failed due: " . $ex->getMessage();
        exit();
    }
}

function getOrder($order_id)
{
    global $db;
    try
    {
        $query = 'SELECT * FROM tblorders'
                . ' WHERE orderNum =:o';
        $statement = $db->prepare($query);
        $statement->bindValue(':o',$order_id);
        $statement->execute();
        $result = $statement->fetch();       

        return $result;
    }
    catch (PDOException $ex) {
        echo "getOrder failed due: " . $ex->getMessage();
        exit();
    }
}

function updtorder($order_id, $quan)
{
    global $db;
    $query_text = "UPDATE tblorders" 
                   ." SET orderQuantity=:quan" 
                   ." WHERE orderNum=:onum";
                                

    try {
        $cmd = $db->prepare($query_text);
        $cmd->bindValue(':quan', $quan);
        $cmd->bindValue(':onum', $order_id);
        $cmd->execute();
        $rowcount = $cmd->rowCount();
        return $rowcount;

    } catch (PDOException $ex) {
        echo "updtorder failed, due: " . $ex->getMessage();
        exit();
    }
}