<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Universal.php';
    include_once '../../../models/Hosts.php';
    include_once '../../../controllers/ErrorController.php';
    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    //Instantiate Users Class
    $univ = new Universal($db);
    $hosts = new Hosts($db);    
    $adminInfo = array();
  

    $res = $hosts->getAdmins();

    if ($res->rowCount() > 0) {
         while ($row = $res->fetch(PDO::FETCH_ASSOC)){
             extract($row);
             if($status=="false"){
                 $status = "UNVERIFIED ";
             }else{
                $status = "VERIFIED";
             }
             $adminData = array(
                 'employee_id' => $employee_id,
                 'fname' => $fname,
                 'mname' => $mname,
                 'lname' => $lname,
                 'status' => $status
             );
             array_push($adminInfo, $adminData);
         }

         echo json_encode ($adminInfo);
    }else{
        echo json_encode (
            array (
                'message' => 'No admin'
            )
        );
    }