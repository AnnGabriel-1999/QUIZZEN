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
    $data = json_decode(file_get_contents('php://input'));

    $res = $univ->selectAll('superadmin', 'id', 1 ,'password', $data->password);
    
    if($res->rowCount() > 0 ){
        if($data->newpw === $data->confpw){
            if($univ->updateSomething('superadmin', 'password', $data->newpw, 'id', 1)){
                echo json_encode(array(
                    'success' => 'password updated'
                ));
            }
        }else{
            echo json_encode(array(
                'dontmatch' => 'be consistent uy'
            ));
        }
    }else{
        echo json_encode(array(
            'wrongpw' => 'wrong password pare'
        ));
    }
    
?>