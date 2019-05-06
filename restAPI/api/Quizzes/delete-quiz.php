<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Universal.php';
    include_once '../../models/Quiz.php';
    include_once '../../controllers/ErrorController.php';
    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    //Instantiate Users Class
    $univ = new Universal($db);
    $quiz = new Quiz($db);
    $quizData = array();

    if($univ->delete1('user_answers', 'quiz_id', $_GET['quizID'])){
        if($univ->delete1('quiz_takers', 'quiz_id', $_GET['quizID'])){
            if($univ->delete1('quiz_tag_colllections', 'quiz_id', $_GET['quizID'])){
                if($univ->delete1('quiz_parts', 'quiz_id', $_GET['quizID'])){
                    if($univ->delete1('answer_choices', 'quiz_id', $_GET['quizID'])){
                        if($univ->delete1('questions', 'quiz_id', $_GET['quizID'])){
                            if($univ->delete1('quizzes', 'quiz_id', $_GET['quizID'])){
                                echo json_encode(array('success' => 'quiz deleted'));
                            }else{
                                echo json_encode(array('error' => '@questions tbl'));
                            }
                        }else{
                            echo json_encode(array('error' => '@questions tbl'));
                        }
                    }else{
                        echo json_encode(array('error' => '@answer_choices tbl'));
                    }
                }else{
                    echo json_encode(array('error' => '@quiz_parts tbl'));
                }
            }else{
                echo json_encode(array('error' => '@quiz_tag_colllections tbl'));
            }
        }else{
            echo json_encode(array('error' => '@quiz_takers tbl'));
        }
    }else{
        echo json_encode(array('error' => '@user_answers tbl'));
    }