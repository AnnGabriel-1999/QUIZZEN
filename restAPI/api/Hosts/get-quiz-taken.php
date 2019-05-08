<?php
 //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';

    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    $quiz = new Quiz($db);

    $result = $quiz->getTakenQuizSection($_GET['section_id']);
    $rowcount = $result->rowCount();

    $quizzArr = array();

    if($rowcount > 0){
        while ($datarow = $result->fetch(PDO::FETCH_ASSOC)){
            
            $quizData = array(
                'quiz_id' => $datarow['quiz_id'],
                'quiz_title' => $datarow['quiz_title'],
                'section' => $datarow['section'],
                'time_started' => $datarow['time_started'],
                'room_id' => $datarow['room_id']
            );
            array_push($quizzArr, $quizData);
        }

        echo json_encode($quizzArr);
    }else{
        echo json_encode(array('error' => 'No Handled Students' ));
    }
?>