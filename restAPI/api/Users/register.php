<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Universal.php';
    include_once '../../controllers/ErrorController.php';
    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    //Instantiate Users Class
    $univ = new Universal($db);
    //Instatiate Error Controller
    $errorCont = new ErrorController();
    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

    if($errorCont->checkField($data->student_id, 'Student ID', 10,11)){
        if($errorCont->checkField($data->username, 'Username' , 8, 31)){
            if($errorCont->checkField($data->password, 'Password', 10, 101)){
                if($errorCont->checkIfMatch($data->password,$data->confirm_pw,"Password")){
                    if($univ->insert3('user_accounts', 'student_id', 'username', 'password', $data->student_id, $data->username, $data->password)){
                       if($univ->updateSomething('students', 'status', 'VERIFIED', 'student_id', $data->student_id)){
                            echo json_encode(array('success' => 'Registration success.'));
                       }else{
                            echo json_encode(array('error' => 'Registration failed.'));
                       }     
                        
                    }else{
                        echo json_encode(array('success' => 'Theres an error.'));
                    }
                }
            }
        }
    }

    if ($errorCont->errors != null) {
        echo json_encode($errorCont->errors);
    }