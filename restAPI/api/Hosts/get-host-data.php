<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Hosts.php';
    include_once '../../controllers/ErrorController.php';

    $database = new Database();
    $db = $database->connect();
    $host = new Hosts($db);
    $errorCont = new ErrorController();
    $hostData = array();
    $res = $host->getHostData($_GET['empID']);

    if($res->rowCount() > 0){
       while($row = $res->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $host_info = array(
                'empID' => $employee_id,
                'fname' => $fname,
                'mname' => $mname, 
                'lname' => $lname,
                'username' => $username,
                'password' => $password
            );
            array_push($hostData, $host_info);
       }
       echo json_encode($hostData);
    }else{
        echo json_encode(array('error' => "not existing"));
    }
    
?>