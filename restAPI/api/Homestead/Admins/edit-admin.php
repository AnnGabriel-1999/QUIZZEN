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

    if($errorCont->checkField($data->empID, 'Employee ID', 10, 11)){
        if($errorCont->checkField($data->fname, 'First Name', 0, 100)){
                if($errorCont->checkField($data->lname, 'Last Name', 0, 100)){
                    if($univ->update4('my_admins', 'employee_id', $data->empID, 'fname', $data->fname, 'mname', $data->mname, 'lname', $data->lname, 'employee_id', $data->oldID)){
                        echo json_encode (
                            array ('success' => 'Successfully updated')
                        );                        
                    }else{
                        echo json_encode(
                            array('error' => 'Theres an error')
                        );
                    }
                    
                }
            }
        }

    if($errorCont->errors != null){
        echo json_encode($errorCont->errors);        
    }
?>