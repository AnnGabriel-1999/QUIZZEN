<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Sections.php';

    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();

    //Instantiate Quiz Class
    $sections = new Sections($db); 
    $result = $sections->getHandledSections($_GET['admin_id']);
    $rowcount = $result->rowCount();
    $courseInfo = array();

    function searchDuplicate($arr, $obj) {
        foreach ($arr as $value) {
            if ($value['course_id'] == $obj['course_id'] && $value['course'] == $obj['course']) {
                return true; //duplicate
            }
        }
        return false;
    };

    if ($rowcount > 0) {
        // Quiz array
        
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $courseDara = array(
                'course_id' => $course_id,
                'course' => $course
            );
            array_push($courseInfo, $courseDara);
        }


        $result = array();
        foreach ($courseInfo as $obj) {
            if (searchDuplicate($result, $obj) === false) {
                $result[] = $obj;
            }
        }

        echo json_encode($result);
    }else{
        echo json_encode(
            array(
                'message' => 'No courses'
             )
        );
    }
        