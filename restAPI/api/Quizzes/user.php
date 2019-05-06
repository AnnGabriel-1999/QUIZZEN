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
    $quiz->admin_id = $_GET['admin_id'];
    //Quiz Query
    $result = $quiz->accountUser();

    //Get Row Count of Students
    $rowcount = $result->rowCount();

    if($rowcount>0){
        // Users array
        $quiz_arr['data'] = array();
    // $quiz_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $quiz_item = array (
                'user' => $fullname
            );

            //Push to data array
        array_push($quiz_arr['data'], $quiz_item);
        }

        //Convert to JSON 
        echo json_encode($quiz_arr['data']);
    }else{
        // No students
        echo json_encode(array(
            'message' => 'No Quiz Created.'
        ));
    }
