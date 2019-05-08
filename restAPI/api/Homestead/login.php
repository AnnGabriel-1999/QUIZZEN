<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Universal.php';
    include_once '../../controllers/ErrorController.php';

  $database = new Database();
  $db = $database->connect();
  $univ = new Universal($db);
    $errorCont = new ErrorController();
    $ids = 0;
  //GETS THE SENT DATA
  $data = json_decode(file_get_contents('php://input'));

  if($errorCont->checkField($data->sa_username,"Username",1,20)){
    if($errorCont->checkField($data->sa_password,"Password",1,20)){
        $res = $univ->selectAll('superadmin', 'username', $data->sa_username, 'password', $data->sa_password);


        if( $res->rowCount() == 1 ){
           while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
               # code...
               extract($row);
               $ids = $id;
           }
           echo json_encode(array('success' => 'Superadmin Login Success.', 'session' => $ids));
        }else{
            echo json_encode(array('error' => 'Login Failed.'));
        }
    }
  }

  if ($errorCont->errors != null) {
    echo json_encode($errorCont->errors);
  }
    
?>