<?php 
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Universal.php';
include_once '../../models/Users.php';
include_once '../../controllers/ErrorController.php';

    $database = new Database();
    $db = $database->connect();
    $users = new Users($db);
    $errorCont = new ErrorController();
    //GETS THE SENT DATA

    $data = json_decode(file_get_contents('php://input'));

    if($users->loginStudent($data->username, $data->password)){
        echo json_encode(array('success' => 'Login Success.' , 'session' => $users->sesClientId , 'name' => $users->client_name ));
    }else{
        echo json_encode(array('error' => 'Login Failed' ));
    }

    
?>