<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../../config/Database.php';
    include_once '../../../models/Universal.php';
    include_once '../../../controllers/ErrorController.php';

	$database = new Database();
	$db = $database->connect();
	$univ = new Universal($db);
    $errorCont = new ErrorController();
	//GETS THE SENT DATA
    $data = json_decode(file_get_contents('php://input'));
    
    if($errorCont->checkField($data->request_id,"Request ID",1,20)){
       if($errorCont->checkField($data->action, "Action", 1, 20)){
        $res = $univ->selectAll2('admin_requests', 'req_id', $data->request_id);
        if ($res->rowCount() == 1) {
           if ($data->action == "GRANT") {
                $request_id = $data->request_id;
                while ($row = $res->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    if ($univ->insert5('my_admins', 'employee_id', 'fname', 'mname', 'lname', 'status', $employee_id, $fname, $mname, $lname, $status)){
                        if($univ->updateSomething('admin_requests', 'status', 'granted', 'req_id', $request_id)){
                            echo json_encode (
                                array (
                                    'success' => 'Request granted.'
                                )
                            );
                        }
                    }else{
                        echo json_encode (
                            array (
                                'message' => 'Theres an error from accepting the request.'
                            )
                        );
                    }
                }
           }elseif($data->action == "DENY"){
                $request_id = $data->request_id;
                if($univ->updateSomething('admin_requests', 'status', 'denied', 'req_id', $request_id)){
                    echo json_encode (
                        array (
                            'success' => 'Request denied.'
                        )
                    );
                }
           }elseif($data->action == "DELETE"){
               $request_id = $data->request_id;
               if($univ->delete1('admin_requests', 'req_id', $request_id)){
                echo json_encode (
                    array (
                        'success' => 'Request deleted.'
                    )
                    );
               }
           }
        } else {
            echo json_encode (
                array (
                    'message' => 'No request found on the given ID'
                )
            );
        }
       }
    }