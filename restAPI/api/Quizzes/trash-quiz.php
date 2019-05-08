<?php
   header('Access-Control-Allow-Origin: *');
   header('Access-Control-Allow-Methods: POST');
   header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

   include_once '../../config/Database.php';
   include_once '../../models/Quiz.php';
   include_once '../../models/Universal.php';
   include_once '../../controllers/ErrorController.php';


 
    $database = new Database();
    $db = $database->connect();
    $univ = new Universal($db); 
    $errorCont = new ErrorController();

    date_default_timezone_set('Asia/Manila');
    
    if($univ->updateSomething('quizzes', 'delete_date',   date("Y-m-d"), 'quiz_id', $_GET['quizID'])){
        echo json_encode(
            array('message'=>'moved to trash', 'TIMEZONE' =>  date("Y-m-d"))
        );
    }else{
        echo json_encode(
            array('error'=>'error moving to trash')
        );
    }
    
?> 