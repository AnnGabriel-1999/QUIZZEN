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

    $result = $quiz->getFailuresOnly($_GET['room_id']);
    $rowcount = $result->rowCount();

    $quizzArr = array();

    if($rowcount > 0){
        while ($datarow = $result->fetch(PDO::FETCH_ASSOC)){
            
            $quizData = array(
                'user_id' => $datarow['user_id'],
                'student_id' => $datarow['student_id'],
                'fname' => $datarow['fname'],
                'mname' => $datarow['mname'],
                'lname' => $datarow['lname'],
                'overall' => $datarow['overall'],
                'score' => $datarow['score'],
                'equivalent' => $datarow['equivalent']
            );
            array_push($quizzArr, $quizData);
        }

        echo json_encode($quizzArr);
    }else{
        echo json_encode(array('error' => 'No Handled Students' ));
    }
?>