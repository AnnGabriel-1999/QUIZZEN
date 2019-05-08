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

    if($errorCont->checkField($data->empID, 'Employee ID', 10, 11)){
        $result = $univ->selectAll2('my_admins', 'employee_id', $data->empID);
        $count = $result->rowCount();
        
        if($count==1) {
            $emp_arr['empData'] = array();
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $emp_array = array (
                    'fname' => $fname,
                    'mname' => $mname,
                    'lname' => $lname,
                    'id' => $employee_id,
                    'status' => $status    
                );
                array_push($emp_arr['empData'], $emp_array);
            }
           
            echo json_encode($emp_arr);
        }else{
            $res = $univ->selectAll('admin_requests', 'employee_id', $data->empID, 'status', 'pending');
            if($res->rowCount() == 1){
                echo json_encode(
                    array ( 'pending' => 'This employee id already exists in our database' )
                ); 
            }
        }

        
    }else{
        echo json_encode(array('error' => 'Search fai3led'));
    }
    
