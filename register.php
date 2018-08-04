<?php
header('Access-Control-Allow-Origin: *');
$mysqlConnection = @mysql_connect("localhost", "thesimplestclicker", "nGpprYndtqZ9x2ym") or die(mysql_error());
mysql_select_db("thesimplestclicker") or die(mysql_error());
mysql_set_charset("utf8");
 
if ((isset($_GET['login']) && isset($_GET['password'])))
{
    if (!empty($_GET['login']) && !empty($_GET['password']))
    {
        $login = filter_var($_GET['login'], FILTER_SANITIZE_STRING);
        $password = filter_var($_GET['password'], FILTER_SANITIZE_STRING);
        $salt = "69fde8079d85efb8a603d37e39717cd4";
        
        $query = "SELECT * FROM user WHERE name = '".$login."'";
        $result = mysql_query($query);
        $rows = mysql_num_rows($result);
        if ($rows>0)
            {
                $myObj = new \stdClass();
                $myObj->value = "The given login exists.";
                $myJSON = json_encode($myObj);
                echo $myJSON;
            }
        else 
            {
                $pw = hash("sha512", $salt.$password);
                $hashedName = hash("sha512", $salt.$login);
                $insert_user = "INSERT INTO user (name, password, hashedName) VALUES ('$login','$pw', '$hashedName')";
                $result = mysql_query($insert_user);
                $myObj = new \stdClass();
                $myObj->value = "Your registration has been successful."; 
                $myJSON = json_encode($myObj);
                echo $myJSON;
            }
        mysql_close($mysqlConnection);
    }        
}
?>
    
