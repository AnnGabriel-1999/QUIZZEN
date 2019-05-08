<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../../../config/Database.php';
include_once '../../../models/Users.php';
include_once '../../../controllers/ErrorController.php';


//Instantiate Database Class
$database = new Database();
$db = $database->connect();

//Instantiate Users Class
$users = new Users($db); 

$errorCont = new ErrorController();

$student_id;

$data = json_decode(file_get_contents('php://input'));

if($errorCont->checkField($data->currentStudId, "New Quiz Title",1,250)){
  if($errorCont->checkField($data->courseId, "New Quiz Description",1,300)){
    if($errorCont->checkField($data->sectionId, "New Quiz Description",1,300)){
      if($errorCont->checkField($data->fname, "New Quiz Description",1,300)){
        if($errorCont->checkField($data->mname, "New Quiz Description",1,300)){
          if($errorCont->checkField($data->lname, "New Quiz Description",1,300)){
            if($errorCont->checkField($data->student_id, "New Quiz Description",1,300)){

              $users->new_id = $data->currentStudId;
              $users->section_id = $data->sectionId;
              $users->fname = $data->fname;
              $users->mname = $data->mname;
              $users->lname = $data->lname;
              $users->course_id = $data->courseId;
              $users->student_id = $data->student_id;

              if($users->updateStudent()){
                echo json_encode( array('success' => 'Updating of Quiz Success.') );
              }else{
                echo json_encode( array('message' => 'Updating of Quiz Success.') );
              }

            }   
          }
        }
      }
    }    
  }
}

if($errorCont->errors != null){
  echo json_encode( $errorCont->errors );
}
