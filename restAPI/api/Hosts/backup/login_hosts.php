<?php 
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Hosts.php';
    include_once '../../controllers/ErrorController.php';

	$database = new Database();
	$db = $database->connect();
	$hosts = new Hosts($db);
  $errorCont = new ErrorController();
	//GETS THE SENT DATA
	$data = json_decode(file_get_contents('php://input'));

  if($errorCont->checkField($data->sent_username,"Username",1,20)){
    if($errorCont->checkField($data->sent_password,"Password",1,20)){
        $hosts->username = $data->sent_username; 
        $hosts->password = $data->sent_password;

        if($hosts->logInHost()){
            echo json_encode(array('success' => 'Host Login Success.' , 'session' => $hosts->admin_id_blah ));
        }else{
            echo json_encode(array('error' => 'Login Failed.'));
        }
    }
  }

  if ($errorCont->errors != null) {
    echo json_encode($errorCont->errors);
  }
    
?>