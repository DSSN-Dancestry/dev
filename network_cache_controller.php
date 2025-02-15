<?php
include 'util.php';
require 'connect.php';
my_session_start();
//var_dump($_SESSION);
if (isset($_SESSION["user_type"])) {
     $user_type=$_SESSION["user_type"];
     if( $user_type==="Admin"){

         $json = file_get_contents('php://input');
         $arr=json_decode($json,JSON_OBJECT_AS_ARRAY);
         $response="Successfully updated ".count($arr)." nodes";
         try {
             $conn=getDbConnection();
             $sql="DELETE FROM network_cache";
             $conn->query($sql);
             $stmt= $conn->prepare("INSERT INTO network_cache VALUES (?,?,?)");
             foreach ($arr as $key=> $value ){
                 $stmt->execute( [$key,$value['x'],$value['y']]);
             }
         } catch (Exception $e){
             $response =  $e->getMessage();
         }
         echo json_encode($response) ;
     }
}
