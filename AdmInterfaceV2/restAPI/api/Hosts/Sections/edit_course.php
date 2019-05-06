<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Courses.php';

    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();

    //Instantiate Users Class
    $courses = new Courses($db); 

    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));
    $courses->course = $data->course;
    $courses->oldCourse = $data->oldCourse;
    $courses->getCourseID();
    

    if($courses->validateCourse()){
        if($courses->editCourse()) {
            echo json_encode(
                array('message' => 'Course updated.')
            );
        }else{
            echo json_encode(
                array('message' => 'Course not update.')
            );
        }
    }else{
         echo json_encode(
             $courses->errors    
         );
    }