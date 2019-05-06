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
    $hosts = new Hosts($db);
    $data = json_decode(file_get_contents('php://input'));
    if($hosts->updateHost($data->empID, $data->fname, $data->mname, $data->lname)){
        echo json_encode(
            array('success' => 'nice one')
        );
    }else{
        echo json_encode(
            array('error' => 'mali')
        );
    }
    
    
?>