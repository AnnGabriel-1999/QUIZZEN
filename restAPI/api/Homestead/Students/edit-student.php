<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../../config/Database.php';
    include_once '../../../models/Users.php';
    include_once '../../../models/Universal.php';
    include_once '../../../controllers/ErrorController.php';

    //Instantiate Classes
    $database = new Database();
    $db = $database->connect();
    $users = new Users($db); 
    $univ = new Universal($db); 
    $errorCont = new ErrorController();

    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

    if($errorCont->checkField($data->student_id,"Student Number",10,11)){
        if($errorCont->checkField($data->fname,"First Name",1,101)){
            if($errorCont->checkField($data->fname,"Lastname Name",1,101)){
                $users->new_id = $data->new_id;
                $users->setStudentID($data->student_id);
                $users->fname = $data->fname;
                $users->mname = $data->mname;
                $users->lname = $data->lname;

                if($users->updateStudent()){
                    if($univ->updateWithKey('students_sections', 'student_id', $data->new_id, 'section_id', $data->section_id, 'student_id', $data->student_id)){
                        echo json_encode(array('success' => 'nice one chiksilog'));
                    }else{
                        echo json_encode(array('success' => 'nice one chiksilog'));
                    }
                    
                }else{
                    echo json_encode(array('error' => 'not nice chiksilog'));
                }
                

                
            }
        }
    }
    

    if($errorCont->errors != null){
        echo json_encode($errorCont->errors);
    }
    