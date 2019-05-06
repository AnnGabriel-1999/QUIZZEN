<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Universal.php'; 
    include_once '../../controllers/ErrorController.php';

    $database = new Database();
    $db = $database->connect();
    $univ = new Universal($db);
    $errorCont = new ErrorController();
   
    $res = $univ->selectAll3('quiz_takers', 'section_id', $_GET['secID'], 'semester_id', $_GET['sy'], 'quiz_id', $_GET['quiz_id']);

    if($res->rowCount() > 0){
        echo json_encode(array('message' => "pre nagquiz na tong mga bata grabe ka"));
    }else{
        echo json_encode(array('success' => "g"));
    }
    
?>