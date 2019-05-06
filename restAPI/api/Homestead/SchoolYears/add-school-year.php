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
    //Instatiate Error Controller
    $errorCont = new ErrorController();
    $x=0;
    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

    $res = $univ->selectAll2('school_years', 'schoolYear', $data->schoolyear);
    $x = $res->rowCount();

    if($x == 0){
        if($errorCont->checkField($data->schoolyear, 'School Year', 1, 150)){
            for ($ctr=1; $ctr<4; $ctr++){
                if($univ->insert2('school_years', 'schoolYear', 'semester', $data->schoolyear, $ctr)){
                    $x++;
                }
            }
        }
    
        if ($x == 3){   
            echo json_encode (array ('success' => "Schoolyear added", 'count' => "$x"));
        }else{
            echo json_encode (array ('error' => 'Schoolyear not added.'));
        }
    
        if($errorCont->errors != null){
            echo json_encode($errorCont->errors);        
        }
    }else{
        echo json_encode(array('message' => $x));
    }

    
?>