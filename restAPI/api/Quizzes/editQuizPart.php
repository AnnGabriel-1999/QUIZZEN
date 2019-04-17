<?php 
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';
    include_once '../../controllers/ErrorController.php';

    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    //Instantiate Quiz Class
    $quiz = new Quiz($db); 
     //Instatiate Error Controller
    $errorCont = new ErrorController();

    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));
 	
 	// VALIDATION

    if ($errorCont->checkField($data->new_part_title, "New Part Title" , 0, 200)) {
        if ($errorCont->checkField($data->type_name, "Type Name" , 0 , 30)) {
            if($errorCont->checkField($data->duration, "Duration", 0, 100)){
                $quiz->new_part_title = $data->new_part_title ;
                $quiz->type_name = $data->type_name; 
                $quiz->getTypeID();
                $quiz->duration = $data->duration;
                $quiz->part_id = $data->part_id ;
    
                if( $quiz->updateQuizPart() ){
                    echo json_encode(array('success' => 'Quiz Part updated successfully.') );
                }else{
                    echo json_encode(array('message' => 'Quiz Part updating failed.') );
                }
            }
        }
    }


    if($errorCont->errors != null){
        echo json_encode(
            $errorCont->errors
        );
    }


?>
