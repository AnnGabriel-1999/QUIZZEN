<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../../../config/Database.php';
include_once '../../../models/Sections.php';
include_once '../../../controllers/ErrorController.php';

$database = new Database();
$db = $database->connect();
$sections = new Sections($db);
$errorCont = new ErrorController();

$sections->setSectionID($_GET['section_id']);
$result = $sections->getStudentsSections();
$rowcount = $result->rowCount();

if($rowcount > 0){

	$sec_arr['data'] = array();

	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            extract($row);
            $quiz_item = array (
                'student_id' => $student_id,
                'section_id' => $section_id,
                'fname' => $fname,
                'mname' => $mname,
                'lname' => $lname,
                'status' => $status,
                'course_id' => $course_id
            );

           array_push($sec_arr['data'], $quiz_item);
    }

    echo json_encode($sec_arr,JSON_PRETTY_PRINT);

}else{
	 echo json_encode(array('message' => 'No Students Here.'));
}

?>
