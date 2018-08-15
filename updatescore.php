<?php
header('Access-Control-Allow-Origin: *');

$mysqlConnection = @mysql_connect("localhost", "jubel_clicker", "nGpprYndtqZ9x2ym") or die(mysql_error());
mysql_select_db("jubel_clicker") or die(mysql_error());

$myObj = new \stdClass();

$rest_json = file_get_contents("php://input"); 
$_PUT = json_decode($rest_json, true);

if (isset($_PUT['cookie']) && isset($_PUT['points']))
    {
        if (!empty($_PUT['cookie']) && !empty($_PUT['points']))
            {
                $cookie = $_PUT['cookie'];
                $points = $_PUT['points'];
                
                $query = "SELECT id FROM user WHERE hashedName = '".$cookie."'";
                $result = mysql_query($query);
                $isExist = mysql_fetch_assoc($result);
                foreach($isExist as $key => $value)
                        {
                            $is = $value;
                        }
                        
                if ($is > 0)
                {
                    $query2 = "SELECT count(1) FROM score WHERE id_user = '".$is."'";
                    $result = mysql_query($query);
                    $isExistInScore = mysql_fetch_assoc($result);
                    foreach($isExistInScore as $key => $value)
                        {
                            $isInScore = $value;
                            
                        }
                        if ($isInScore > 0 )
                        {
                            $insert_score = "UPDATE score SET points='".$points."' WHERE id_user = '".$is."'";
                            $result = mysql_query($insert_score) or die(mysql_error());
                                            
                            $myObj->value = "Save your score has been successful."; 
                            $myJSON = json_encode($myObj);
                            http_response_code(200);
                            echo $myJSON;
                        }
                        else
                        {
                            $insert_score = "INSERT INTO score(id_user, points) VALUES ('".$is."','".$points."')";
                            $result = mysql_query($insert_score);
                                            
                            $myObj->value = "Save your score has been successful."; 
                            $myJSON = json_encode($myObj);
                            http_response_code(200);
                            echo $myJSON;
                        }
                    
                }
                else 
                {
                    $myObj->value = "The user specified does not exist."; 
                    $myJSON = json_encode($myObj);
                    http_response_code(401);
                    echo $myJSON;
                }
            }
    }
