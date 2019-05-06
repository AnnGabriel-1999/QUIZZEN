<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../../config/Database.php';
    include_once '../../../models/Universal.php';
    include_once '../../../controllers/ErrorController.php';
    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    //Instantiate Users Class
    $univ = new Universal($db);
    //Instatiate Error Controller
    $errorCont = new ErrorController();
    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

    if($errorCont->checkField($data->course_name, 'Course Name', 1, 150)){
        if($errorCont->checkField($data->course_prefix, 'Course Prefix', 1, 150)){
            $res = $univ->selectAll2('courses', 'course', $data->course_name);
            if($res->rowCount() > 0){
                json_encode (array ('error' => "$data->course_name already exists in our database."));
            }else{
                if($univ->insert2('courses', 'course', 'course_prefix', $data->course_name, $data->course_prefix)){
                    echo json_encode (array ('success' => 'Course added.', 'lol' => $res->rowCount()));
                }else{
                    echo json_encode (array ('error' => 'Theres an error.'));
                }
            }
            json_encode (array ('error' => "$data->course_name already exists in our database."));
        }
    }

    if($errorCont->errors != null){
        echo json_encode($errorCont->errors);        
    }
?>