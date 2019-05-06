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

    $result = $quiz->answerBy($_GET['user_id'],$_GET['room_id']);
    $rowcount = $result->rowCount();

    $quizzArr = array();

    if($rowcount > 0){
        while ($datarow = $result->fetch(PDO::FETCH_ASSOC)){
            
            $quizData = array(
                'quiz_title' => $datarow['quiz_title'],
                'fname' => $datarow['fname'],
                'mname' => $datarow['mname'],
                'lname' => $datarow['lname'],
                'student_id' => $datarow['student_id'],
                'course_prefix' => $datarow['course_prefix'],
                'section' => $datarow['section']
            );
            array_push($quizzArr, $quizData);
        }

        echo json_encode($quizzArr);
    }else{
        echo json_encode(array('error' => 'No Handled Students' ));
    }
?>