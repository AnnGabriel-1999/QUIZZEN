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
    $ctr = 0;
    //Instatiate Error Controller
    $errorCont = new ErrorController();
    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

    $admin_ids = explode(',', $data->admin_ids);

    foreach($admin_ids as $admID){
        $res = $univ->selectAll3('sections_handled', 'section_id', $data->section_id, 'admin_id',  $admID, 'schoolyear_id', $data->schoolyear_id);

        if($res->rowCount() > 0){
            while($row = $res->fetch(PDO::FETCH_ASSOC)){    
                extract($row);
                if($univ->updateSomething('sections_handled', 'flag_handle', null, 'handling_id', $handling_id)){
                    $ctr++;
                }else{
                    echo json_encode(
                        array(
                            'error' => 'das'
                        )
                    );
                }

            }
        }else{
            if( $univ->insert3('sections_handled', 'admin_id', 'section_id', 'schoolyear_id', $admID, $data->section_id, $data->schoolyear_id)) {
                $ctr++;
            }else{
                echo json_encode(
                    array(
                        'error' => 'das'
                    )
                );
            }
        }

    
    }

    if($ctr == count($admin_ids)){
        echo json_encode(
            array(
                'message' => 'Success'
            )
        );
    }

