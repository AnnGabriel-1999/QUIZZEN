<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';
    include_once '../../controllers/ErrorController.php';

    $database = new Database();
    $db = $database->connect();
    $quiz = new Quiz($db); 
    $errorCont = new ErrorController();

    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

     if($errorCont->checkField($_POST['question'], "Question",1,1001)){
         if($errorCont->checkField($_POST['a'], 'Value of A', 0, 201)){
             if($errorCont->checkField($_POST['b'], 'Value of B', 0, 201)){
                 if($errorCont->checkField($_POST['c'], 'Value of C', 0, 201)){
                     if($errorCont->checkField($_POST['d'], 'Value of D', 0, 201)){
                          if(isset($_FILES['file']['tmp_name'])) {
                                $filepath = '../../../AdmInterfaceV2/uploads/quiz/'.$_FILES['file']['name'];
                                  $quiz->filepath = $filepath;
                              }
                           $quiz->question = $_POST['question'];
                            $quiz->question_id = $_POST['question_id'];
                            array_push($quiz->values, $_POST['a']);
                            array_push($quiz->values, $_POST['b']);
                            array_push($quiz->values, $_POST['c']);
                            array_push($quiz->values, $_POST['d']);
                             if($quiz->updateQuestion()){
                                 $quiz->fetchChoices();
                                 $ctr = 0;
                                 for ($x=0; $x<4; $x++){
                                     if($quiz->updateAnswerChoices($quiz->choices_keys[$x], $quiz->values[$x])){
                                         $ctr++;
                                     }
                                 }
                                 
                                 if($ctr==4){
                                     $result = $quiz->selectAllArrange('answer_choices', 'question_id', $quiz->question_id);
                                     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                         extract($row);
                                         echo json_encode( array("success' => 'Question updated."));
                                         if(isset($_FILES['file']['tmp_name'])) {
                                             move_uploaded_file($_FILES['file']['tmp_name'], $filepath);
                                         }
                                     }
                                  
                                 }
                               
                             }else{
                                 echo json_encode( array('error' => 'Theres an error modifying the question.') );
                             }
                         }
                     }
                 }
             }
     }
