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

   $tag_id = $_POST['tag_id'];

   if($univ->delete1('quiz_tag_colllections', 'tag_id', $tag_id)){
       if($univ->delete1('quiz_tags',  'tag_id', $tag_id )){
           echo json_encode(array('success' => 'deleted'));
       }else{
           echo json_encode(array('error' => 'somethins wrong'));
       }
   }else{
       echo json_encode(array('error' => 'somethins wrong'));
   }