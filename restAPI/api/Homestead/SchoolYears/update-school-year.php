<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../../config/Database.php';
    include_once '../../../models/Quiz.php';
    include_once '../../../models/Universal.php';

    $database = new Database();
    $db = $database->connect();
    $quiz = new Quiz($db); 
    $univ = new Universal($db); 

    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

                $quiz->schID = $data->schID;
                $quiz->schoolYear = $data->schoolYear;



                if($quiz->updateSY()){
                    echo json_encode( array('success' => 'School Year Updated Successfully.') );
                }else{
                    echo json_encode( array('message' => 'Oops something went wrong.') );
                }
?>