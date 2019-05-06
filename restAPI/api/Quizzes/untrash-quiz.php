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

  
    
    if($univ->updateSomething('quizzes', 'delete_date',   null, 'quiz_id', $_GET['quizID'])){
        echo json_encode(
            array('message'=>'Removed to trash', 'TIMEZONE' => date_default_timezone_get())
        );
    }else{
        echo json_encode(
            array('error'=>'error Removing to trash')
        );
    }
    
?> 