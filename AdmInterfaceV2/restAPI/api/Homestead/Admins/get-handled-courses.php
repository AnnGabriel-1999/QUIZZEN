<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../../config/Database.php';
    include_once '../../../models/Hosts.php';
    include_once '../../../controllers/ErrorController.php';

    $database = new Database();
    $db = $database->connect();
    $hosts = new Hosts($db);
    $errorCont = new ErrorController();
    //GETS THE SENT DATA
  
    $res = $hosts->getHandledCourses($_GET['empID']);
    $courses_arr = array();
    $courses_arr['data'] = array();

   
    if($res->rowCount() > 0){
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $course_item = array (
                'course' => $course
            );
        
            //Push to data array 
            array_push($courses_arr['data'], $course_item);
            //Convert to JSON
            
            
        }
    
        echo json_encode(array_unique($courses_arr));
    }else{
        echo json_encode(array('nothing' => 'empty search'));
    }
