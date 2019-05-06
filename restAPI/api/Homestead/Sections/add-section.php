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
    $Course_id = 0;
    //Instatiate Error Controller
    $errorCont = new ErrorController();
    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

    if($errorCont->checkField($data->courseID, 'Course ID', 1, 150)){
        if($errorCont->checkField($data->section_name, 'Section Name', 1, 150)){
            if($errorCont->checkField($data->year_level, 'Year Level', 1, 2)){
                
                $res = $univ->selectAll('sections', 'course_id', $data->courseID, 'section', $data->section_name);
                    
                if($res->rowCount() == 0) {
                    if($univ->insert3('sections', 'course_id', 'section', 'year_level' ,$data->courseID, $data->section_name, $data->year_level)){
                        echo json_encode(array ('success' => 'Section added.'));
                    }else{
                        echo json_encode(array ('error' => 'Section error.'));
                    }
                }else{
                    echo json_encode(
                        array(
                            'message' => 'Existing na po'
                        )
                    );  
                }
              
            }
        }
    }

    if($errorCont->errors != null) {
        echo json_encode (
            $errorCont->errors  
        );
    }