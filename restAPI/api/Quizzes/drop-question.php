<?php
   header('Access-Control-Allow-Origin: *');
   header('Access-Control-Allow-Methods: POST');
   header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

   include_once '../../config/Database.php';
   include_once '../../models/Quiz.php';
   include_once '../../models/Universal.php';
   include_once '../../controllers/ErrorController.php';

   // Instantiate Classes
   $database = new Database();
   $db = $database->connect();
   $quiz = new Quiz($db);
   $univ = new Universal($db);
   $errorCont = new ErrorController();
   $ctr = 0;

   $quesID = $_POST['question_id'];

         if($univ->deleteQuestion('questions', 'question_id', $quesID)){
       if($univ->deleteQuestion('answer_choices',  'question_id', $quesID )){
           echo json_encode(array('success' => 'deleted'));
       }else{
           echo json_encode(array('error' => 'somethins wrong'));
       }
   }else{
       echo json_encode(array('error' => 'somethins wrong'));
   }