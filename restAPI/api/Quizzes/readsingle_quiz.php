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

    //Get ID from URL
    $quiz->quizID = isset($_GET['quizID']) ? $_GET['quizID'] : die();

    //Get Post
    $result = $quiz->singleQuiz();

    $row = $result->fetch(PDO::FETCH_ASSOC);

    $quiz_item = array (
                'quiz_id' =>$row['quiz_id'],
                'quiz_title' =>$row['quiz_title'],
                'description' => $row['description'],
                'date_created' => $row['date_created'],
                'MaxID' => $row['MaxID'],
                'partsTotal' => $row['partsTotal'],
                'MaxPart' => $row['MaxPart'],
                'quizOwner' =>$row['quizOwner']
            );

    echo json_encode($quiz_item);
