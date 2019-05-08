<?php
include_once '../../config/Database.php';
include_once '../../models/Quiz.php';
include_once '../../controllers/ErrorController.php';
include_once '../../models/Courses.php';
include_once '../../models/Sections.php';
include_once '../../models/Users.php';

$database = new Database();
$db = $database->connect();
$quizModel = new Quiz($db);

$myArray = array();

$quizModel->quizID = $_GET['quizID'];
$result = $quizModel->singleQuiz();
$result2 = $quizModel->viewQuizParts();

$myArray['quiz'] = array();
while($datarow = $result->fetch(PDO::FETCH_ASSOC)){
    $quiz_item = array(
        'quiz_title' => $datarow['quiz_title'],
        'admin_id' => $datarow['admin_id'],
        'description' => $datarow['description'],
        'filepath' => $datarow['filepath'],
        'date_created' => $datarow['date_created'],
        'part_type' => $datarow['part_type']
    );
    array_push($myArray['quiz'], $quiz_item);
}

$myArray['parts'] = array();
while($datarow2 = $result2->fetch(PDO::FETCH_ASSOC)){
    $parts_item = array();
    $parts_item['partatt'] = array(
        'part_title' => $datarow2['part_title'],
        'part_id' => $datarow2['part_id'],
        'totalQs' => $datarow2['totalQs'],
        'duration' => $datarow2['duration'],
        'type' => $datarow2['type'],
        'type_id' => $datarow2['type_id'],
        'description' => $datarow2['description']
    );
    
    array_push($myArray['parts'], $parts_item);
}

echo json_encode($myArray,JSON_PRETTY_PRINT);