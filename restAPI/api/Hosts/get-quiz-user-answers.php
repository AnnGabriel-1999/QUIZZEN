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

    $result = $quiz->getStudentsAnswer($_GET['quiz_id'] , $_GET['room_id'] ,$_GET['user_id'] );
    $rowcount = $result->rowCount();

    $quizzArr = array();

    if($rowcount > 0){
        while ($datarow = $result->fetch(PDO::FETCH_ASSOC)){
            
            $quizData = array(
                'question' => $datarow['question'],
                'student_section_id' => $datarow['student_section_id'],
                'schoolyear_id' => $datarow['schoolyear_id'],
                'section' => $datarow['section'],
                'answer' => $datarow['answer'],
                'status' => $datarow['status'],
                'value' => $datarow['value']
            );
            array_push($quizzArr, $quizData);
        }

        echo json_encode($quizzArr);
    }else{
        echo json_encode(array('error' => 'No Handled Students' ));
    }
?>