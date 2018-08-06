<?php
header('Access-Control-Allow-Origin: *');
//echo "lalalal";
$mysqlConnection = @mysql_connect("localhost", "jubel_clicker", "nGpprYndtqZ9x2ym") or die(mysql_error());
mysql_select_db("jubel_clicker") or die(mysql_error());
mysql_set_charset("utf8");
$myObj = new \stdClass();

$rest_json = file_get_contents("php://input"); 
$_POST = json_decode($rest_json, true);

if ((isset($_POST['login']) && isset($_POST['password'])))
{
    if (!empty($_POST['login']) && !empty($_POST['password']))
    {
        $login = filter_var($_POST['login'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        $salt = "69fde8079d85efb8a603d37e39717cd4";
        if (strlen($login)>16)
            {
                $myObj->value = "The given login is to long.";
                $myJSON = json_encode($myObj);
                http_response_code(409);
                echo $myJSON;
            }
        else {
            if(strlen($password)<4 || strlen($password)>16)
                {
                    $myObj->value = "The password should be between 4 and 16 characters long.";
                    $myJSON = json_encode($myObj);
                    http_response_code(409);
                    echo $myJSON;
                }
            else {
                $query = "SELECT count(1) FROM user WHERE name = '".$login."'";
                $result = mysql_query($query);
                $isExist = mysql_fetch_assoc($result);
                foreach($isExist as $key => $value)
                        {
                            $is = $value;
                        }
                if ($is>0)
                {
                    $myObj->value = "The given login exists.";
                    $myJSON = json_encode($myObj);
                    http_response_code(409);
                    echo $myJSON;
                }
                else 
                {
                    $pw = hash("sha512", $salt.$password);
                    $hashedName = hash("sha512", $salt.$login);
                    $insert_user = "INSERT INTO user (name, password, hashedName) VALUES ('$login','$pw', '$hashedName')";
                    $result = mysql_query($insert_user);
                
                    $myObj->value = "Your registration has been successful."; 
                    $myJSON = json_encode($myObj);
                    http_response_code(201);
                    echo $myJSON;
                }
            }
        }
        
        mysql_close($mysqlConnection);
    }
    else {
        http_response_code(403);
        $myObj->value = "You have not entered a login or password. Try again."; 
        $myJSON = json_encode($myObj);
        echo $myJSON;
        }
}
?>
    
