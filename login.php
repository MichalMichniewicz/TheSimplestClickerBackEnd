<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

header('Access-Control-Allow-Origin: *');

$mysqlConnection = @mysql_connect("localhost", "jubel_clicker", "nGpprYndtqZ9x2ym") or die(mysql_error());
mysql_select_db("jubel_clicker") or die(mysql_error());

$myObj = new \stdClass();

$rest_json = file_get_contents("php://input"); 
$_POST = json_decode($rest_json, true);

    if (isset($_POST['login']) && isset($_POST['password']))
    {
        if (!empty($_POST['login']) && !empty($_POST['password']))
            {
                http_response_code(200);
                $login = filter_var($_POST['login'], FILTER_SANITIZE_STRING);
                $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
                     
                                
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
                    $salt = "69fde8079d85efb8a603d37e39717cd4";                
                    $pw = hash("sha512", $salt.$password);
                    
                    $query = "SELECT hashedName, count(1) as isUser FROM user WHERE name = '".$login."' AND password = '".$pw."';";
                    $result = mysql_query($query);
                    $rows = mysql_num_rows($result);
                    $hashedName = mysql_fetch_assoc($result);
                    foreach($hashedName as $key => $value)
                        {
                            switch ($key)
                            {    
                            case "hashedName":
                                $sent = $value;
                                break;
                                case "isUser":
                                    $isUser = $value;
                                    break;
                            }
                                
                        }
                    if ($isUser>0)
                    {
                        
                        $myObj->value = "$sent";
                        $myJSON = json_encode($myObj);
                        //header('HTTP/1.1 401 Unauthorized', true, 401);
                        http_response_code(200);
                        echo $myJSON;
                    }
                    else
                    {
                        $myObj->value = "Incorrect login or password. Try again.";
                        $myJSON = json_encode($myObj);
                        http_response_code(401);
                        echo $myJSON;  
                    }
                    
                }
                }
            }
            else
            {
                http_response_code(403);
                $myObj->value = "You have not entered a login or password. Try again.";
                $myJSON = json_encode($myObj);
                echo $myJSON;  
            }
                    
         }   
            
?>