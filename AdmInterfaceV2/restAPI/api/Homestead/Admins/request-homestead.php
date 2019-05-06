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
                   // if($errorCont->checkField($data->message, 'Message', 0, 251)){
                        $res = $univ->selectAll('admin_requests', 'employee_id', $data->empID, 'status', 'pending');
                        if($res->rowCount() == 1){
                            echo json_encode(
                                array ( 'pending' => 'This employee id already exists in our database' )
                            );
                        }else{
                            if($univ->insert6('admin_requests', 'employee_id' ,'fname', 'mname', 'lname', 'message', 'status', 
                             $data->empID, $data->fname, $data->mname, $data->lname, $data->message, $data->status)){

                                echo json_encode (array ('success' => 'Request successfully created.', 'CTR' => $res->rowCount()));
                            }else{
                                echo json_encode (array ('error' => 'Request failed'));
                            }
                        }
                  //  }
                }
            }
        }

    if($errorCont->errors != null){
        echo json_encode($errorCont->errors);        
    }
?>