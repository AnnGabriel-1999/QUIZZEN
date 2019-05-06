<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../../config/Database.php';
    include_once '../../../models/Universal.php';
    include_once '../../../controllers/ErrorController.php';

    $database = new Database();
    $db = $database->connect();
    $univ = new Universal($db);
    $errorCont = new ErrorController();
    //GETS THE SENT DATA
  
    if($univ->updateSomething('sections_handled', 'flag_handle', 0, 'handling_id', $_GET['hID'])){
        echo json_encode(array('success' => 'unassigned'));
    }else{
        echo json_encode(array('error' => 'error'));
    }
    