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

	//dito ka mag query na pag insert nung quiz
	//$quizModel->shareQuiz(quizId,QuizName,yungPic,asdasdsad);
}

$myArray['parts'] = array();
while($datarow2 = $result2->fetch(PDO::FETCH_ASSOC)){
    $parts_item = array(
        
        'part_title' => $datarow2['part_title'],
        'part_id' => $datarow2['part_id'],
        'totalQs' => $datarow2['totalQs'],
        'duration' => $datarow2['duration'],
        'type' => $datarow2['type'],
        'type_id' => $datarow2['type_id'],
        'description' => $datarow2['description']
    );

    //papasok mo yung part
    //quizModel->sharePart(adasdasdasdasdasdas.d.as.das.d.asd.asd.);

    $qqquestions = array();
        $quizModel->partID = $datarow2['part_id'];
        $result3 = $quizModel->readQuestions();
        while($datarow3 = $result3->fetch(PDO::FETCH_ASSOC)){
            array_push($qqquestions, array(
            'question' => $datarow3['question'],
            'question_id' => $datarow3['question_id'],
            'quiz_id' => $datarow3['quiz_id'],
            'part_id' => $datarow3['part_id'],
            'rightAnswer' => $datarow3['rightAnswer'],
            'choice1' => $datarow3['choice1'],
            'choice2' => $datarow3['choice2'],
            'choice3' => $datarow3['choice3'],
            'choice4' => $datarow3['choice4'],
            'filepath' => $datarow3['filepath']
        ));

            //quizModel->shareQuestions();
        }
    
    array_push($parts_item,$qqquestions);
    array_push($myArray['parts'], $parts_item);
}

echo json_encode($myArray,JSON_PRETTY_PRINT);