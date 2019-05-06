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
   
    $result = $courses->getAllCourses();
    $rowcount = $result->rowCount();

     if ($rowcount > 0) {
        // Quiz array
        $course_arr['data'] = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $course = array(
                'course_id' => $course_id,
                'course' => $course,
                'course_prefix' => $course_prefix
            );

            // Push to data array
            array_push($course_arr['data'], $course);
        }

        //Convert to JSON
        echo json_encode($course_arr['data']);
    }