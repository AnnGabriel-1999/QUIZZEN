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

   $quizID = $_GET['quizID'];
   $tags = explode(',' , $_GET['tags']);

   if(!empty($tags)){
        foreach($tags as $tag){
            $res = $univ->selectAll('quiz_tag_colllections', 'tag_id', $tag, 'quiz_id', $quizID);
            $numRows =  $res->rowCount();
            if($res->rowCount() < 1){
                $univ->insert2('quiz_tag_colllections', 'tag_id', 'quiz_id', $tag, $quizID);
                $ctr++;
            }elseif($res->rowCount() > 0){
                $ctr++;
            }
        }    
   }

   if($ctr==sizeof($tags)){
       echo json_encode(array('success' => 'yehey', 'counter' => $numRows));
   }else{
       echo json_encode(array('error' => 'somethins wrong', 'counter' => $numRows));
   }