<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Universal.php';
    include_once '../../controllers/ErrorController.php';

    // Instantiate Classes
    $database = new Database();
    $db = $database->connect();
    $univ = new Universal($db);
    $errorCont = new ErrorController();
    // Get Raw Data
    $data = json_decode(file_get_contents('php://input'));
    if($errorCont->checkField($data->tagname, 'Tag Name', 0, 100)){
        $res = $univ->selectAll('quiz_tags', 'tag_name', $data->tagname, 'admin_id', $data->admin_id);
        if($res->rowCount() > 0){
            echo json_encode(
                array ( 'message' => 'This tag already exists in our database' )
            );
        }else{
            if($univ->insert2('quiz_tags', 'admin_id', 'tag_name', $data->admin_id, $data->tagname)){
                echo json_encode(
                    array ( 'message' => 'Tag created')
                );
            }else{
                echo json_encode(
                    array ( 'message' => 'There is an error upon tag creation')
                );
            }
        }
    }

    if($errorCont->errors != null){
        echo json_encode (
            $errorCont->errors
        );
    }