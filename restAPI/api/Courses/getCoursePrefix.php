<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Courses.php';

    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();

    //Instantiate Quiz Class
    $courses = new Courses($db); 
    $courses->setCourseID($_GET['course_id']);
    
    $result = $courses->getCoursePrefix();
    $rowcount = $result->rowCount();

     if ($rowcount > 0) {
        // Quiz array
        $prefix['data'] = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $pref = array(
                'prefix' => $course_prefix
            );

            // Push to data array
            array_push($prefix['data'], $pref);
        }

        //Convert to JSON
        echo json_encode($prefix['data']);
    }