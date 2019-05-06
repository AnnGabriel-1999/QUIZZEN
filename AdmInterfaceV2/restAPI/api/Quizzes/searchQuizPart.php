<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Headers: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';

    // Instantiate Database Class
    $database = new Database();
    $db = $database->connect();

    // Instantiate Quiz Class
    $quiz = new Quiz($db);

    // View Query
    $result = $quiz->searchQuizPart();
    
    // Get Row Count of Quiz
    $rowcount = $result->rowCount();

    if ($rowcount > 0) {
        // Quiz array
        $quiz_arr = array();
        $quiz_arr['data'] = array();
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $quiz_item = array(
                'Part_Title:' => $part_title,
                'Duration:' => $duration,
                'Type:' => $type
            );
            
            // Push to data array
            array_push($quiz_arr['data'], $quiz_item);
        }
        
        // Convert to JSON
        echo json_encode($quiz_arr);
    } else {
        // No Quiz
        echo json_encode(array(
            'message' => 'No Quiz Part Found.'
        ));
    }