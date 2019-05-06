<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';

    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();

    //Instantiate Quiz Class
    $quiz = new Quiz($db); 
    
    //Get Post
    $result = $quiz->searchQuiz();

   //Get Row Count of Students
    $num = $result->rowCount();
   
    if($num>0){
        // Users array
        $quiz_arr = array();
        $quiz_arr['data'] = array();
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $quiz_item = array (
                'Quiz_ID:' => $quiz_id,
                'Quiz_Title:' => $quiz_title,
                'Quiz_Parts:' => $partsperQuiz
            );
        
            //Push to data array 
            array_push($quiz_arr['data'], $quiz_item);
        }
        
        //Convert to JSON
        echo json_encode($quiz_arr);
    }else{
        // No Quiz
        echo json_encode(array(
            'message' => 'No Quiz match to Database.'
        ));
    }