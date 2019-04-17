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
  
    $res = $univ->selectAll2('my_admins', 'employee_id', $_GET['empID']);
    $emp_arr = array();
    $emp_arr['data'] = array();

    while($row = $res->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $emp_item = array (
            'empID' => $employee_id,
            'fname' => $fname,
            'mname' => $mname,
            'lname' => $lname
        );
    
        //Push to data array 
        array_push($emp_arr['data'], $emp_item);
        //Convert to JSON
        echo json_encode($emp_arr);
    }
