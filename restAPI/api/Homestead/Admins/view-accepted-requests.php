<?php
        //Headers
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        include_once '../../../config/Database.php';
        include_once '../../../models/Universal.php';

        //Instantiate Database Class
        $database = new Database();
        $db = $database->connect();

        //Instantiate Quiz Class
        $univ = new Universal($db);

        $res = $univ->selectAll2('admin_requests', 'status', $_GET['status']);

        if($res->rowCount() > 0) {
            $employeeData = array();
            while ($row = $res->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $empInfo = array(
                    'request_id' => $req_id,
                    'employee_id' => $employee_id,
                    'fname' => $fname,
                    'mname' => $mname,
                    'lname' => $lname,
                    'message' => $message,
                    'date_requested' => $date_requested
                );
                array_push($employeeData, $empInfo);
            }
            echo json_encode($employeeData);
        }else{
            echo json_encode(array('message' => 'No requests'));
        }
