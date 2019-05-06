<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Hosts.php';
include_once '../../controllers/ErrorController.php';

  $database = new Database();
  $db = $database->connect();
  $hosts = new Hosts($db);
  $errorCont = new ErrorController();

  $dara_arr = array();

  $resultCourses= $hosts->listCourses();
  $rowcountCourses = $resultCourses->rowCount();

  $resultSections = $hosts->listSections();
  $rowcountSections = $resultSections->rowCount();  

  $resultNamesA = $hosts->listNamesA();
  $rowcountNamesA = $resultNamesA->rowCount();

  $resultNamesS = $hosts->listNamesS();
  $rowcountNamesS = $resultNamesS->rowCount();


  //LALAGAY SA ARRAY YUNG SA COURSE
  if($rowcountCourses>0){
    $dara_arr['courses'] = array();
    
    while ($row = $resultCourses->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $course_item = array (
            'course_id' => $course_id,
            'course' => $course
        );
        array_push($dara_arr['courses'], $course_item);
    }
  }


  //LALAGAY SA ARRAY YUNG SA SECTION
if($rowcountSections>0){
    $dara_arr['sections'] = array();

    while ($row = $resultSections->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $quiz_item = array (
            'section_id' => $section_id,
            'course_id' => $course_id,
            'section' => $section,
            'course' => $course,
            'prefix' => $course_prefix
            
        );
        array_push($dara_arr['sections'], $quiz_item);
    }
  }

  //LALAGAY SA ARRAY YUNG NAME NG ADMINS
  $dara_arr['names'] = array();

  if($rowcountNamesA>0){
    $dara_arr['names']['admins'] = array();

    while ($row = $resultNamesA->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $name_item = array(
        'name' => $username
      );

      array_push($dara_arr['names']['admins'],$name_item);
    }
  }

  if($rowcountNamesS>0){
    $dara_arr['names']['students'] = array();

    while ($row = $resultNamesS->fetch(PDO::FETCH_ASSOC)) {
      extract($row);
      $name_item = array(
        'student_id' => $student_id,
        'firstname' => $fname,
        'middlename' => $mname,
        'lastname' => $lname,
        'section' => $section,
        'course' => $course
      );

      array_push($dara_arr['names']['students'], $name_item);
    }
  }
  
    echo json_encode($dara_arr,JSON_PRETTY_PRINT);
?>