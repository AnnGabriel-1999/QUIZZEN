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
    $quizzes = new Quiz($db);
    $errorCont = new ErrorController();

    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

    if($errorCont->checkField($data->type_name, 'Type Name', 10, 21)){
        if($errorCont->checkField($data->quizID, 'Quiz ID', 0, 1001)){
            if($errorCont->checkField($data->part_title, 'Part Title', 1, 201)){
              if($quizzes->checkPartTitle($data->part_title, $data->quizID) == 0){
                if($errorCont->checkField($data->duration, 'Duration', 0, 4)){
                    if($errorCont->numbersOnly($data->duration, 'Duration')){
                        $quizzes->type_name = $data->type_name;
                        $quizzes->quizID = $data->quizID;
                        $quizzes->part_title = $data->part_title;
                        $quizzes->duration = $data->duration;
                        $quizzes->getTypeID();

                        //Create
                        $quizzes->countParts();
                        $quizzes->totalParts += 1;
                        if ($quizzes->addQuizPart()){

                            echo json_encode(
                                array('success' => 'Quiz part added.',
                                        'num parts' => $quizzes->countParts() 
                                        )
                            );
                        }else{
                            echo json_encode(
                                array('message' => 'There is an error.')
                            ); 
                        }
                    }
                }
              }else{
                  echo json_encode(
                      array("error" => "Part Title already exists")
                  );
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