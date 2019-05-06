<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Universal.php';
    include_once '../../../controllers/ErrorController.php';
    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    //Instantiate Users Class
    $univ = new Universal($db);

    $res = $univ->select3WithSubquery('courses c', 'c.course_id', 'c.course', 'c.course_prefix', '(select count(course_id) from sections where course_id = c.course_id)', 'sections');

    if ($res->rowCount() > 0){
        $courseData = array();
        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $courseInfo = array(
                'course_id' => $course_id,
                'course' => $course,
                'course_prefix' => $course_prefix,
                'sections' => $sections
            );
            array_push($courseData, $courseInfo);
        }
        echo json_encode($courseData);
    }else{
        echo json_encode (array ('message' => 'No sections yet.'));
    }