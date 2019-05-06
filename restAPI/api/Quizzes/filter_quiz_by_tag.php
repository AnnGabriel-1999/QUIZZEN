<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';
    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    //Instantiate Quiz Clssss
    $quiz = new Quiz($db);

    $result = $quiz->filterQuizByTag($_GET['admin_id'], $_GET['tag_id']);

    if($result->rowCount() > 0) {
        $tagged_quizzes['data'] = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $tagged_quiz_item = array(
                'QuizID' => $quiz_id,
                'TagID' => $tag_id,
                'TagName' => $tag_name,
                'QuizTitle' => $quiz_title,
                'Description' => $description, 
                'Filepath' => $filepath,
                'DateCreated' => $date_created,
                'PartType' => $part_type,
                'quizOwner' => $quizOwner,
                'capability' => $capability
            );
            array_push($tagged_quizzes['data'], $tagged_quiz_item);
        }
        echo json_encode($tagged_quizzes);
    }else{
        echo json_encode(array('message' => 'This folder is empty'));
    }
