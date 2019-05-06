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
        $quiz->quizID = $_GET['quiz_id'];
        $result = $quiz->readFreeFlow();

        $quiz_arr['data'] = array();

        if($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $freeflow_item = array (
                    'part_id' => $part_id,
                    'type_id' => $type_id,
                    'quiz_id' => $quiz_id, 
                    'part_title' => $part_title,
                    'duration' => $duration,
                    'position' => $position
                );
            array_push($quiz_arr['data'], $freeflow_item);
        }

        echo json_encode($quiz_arr['data']);

?>