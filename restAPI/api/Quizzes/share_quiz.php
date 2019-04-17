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
        'quiz_id' => $datarow['quiz_id'],
        'quiz_title' => $datarow['quiz_title'],
        'admin_id' => $datarow['admin_id'],
        'description' => $datarow['description'],
        'filepath' => $datarow['filepath'],
        'date_created' => $datarow['date_created'],
        'part_type' => $datarow['part_type']
    );
  //insertion ng kinuhang quiz to new admin
  $quizModel->quizTitle = $datarow['quiz_title'];
  $quizModel->description = $datarow['description'];
  $quizModel->admin_id = $_GET['admID'];
  $quizModel->part_type = $datarow['part_type'];

  if ($quizModel->addQuiz()) {
      echo json_encode(array('success' => 'Quiz created successfully!',
                            $_GET['admID']  ));

      if(isset($_FILES['file']['tmp_name'])) {
          move_uploaded_file($_FILES['file']['tmp_name'], $filepath);
      }
  } else {
      echo json_encode(array('error' => 'Failed to create quiz!'));
  }
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
        //PAG INSERT NAMAN TO NG MGA NAKUHA KONG PARTS
        if ($datarow2['type_id'] == 1){
          $type='Multiple Choice';
          }else if($datarow2['type_id'] == 2){
            $type='True or False';
            }else if($datarow2['type_id'] == 3){
              $type='Arrange The Sequence';
              }else{
                $type='Guess the Word';
                }

        $quizModel->type_name = $type;
        $quizModel->quizID =$_GET['MaxID']+1;
        $quizModel->part_title =$datarow2['part_title'];
        $quizModel->duration = $datarow2['duration'];
        $quizModel->getTypeID();

        //Create
        $quizModel->countParts();
        $quizModel->totalParts += 1;
        if ($quizModel->addQuizPart()){

            echo json_encode(
                array('success' => 'Quiz part added.',
                        'num parts' => $quizModel->countParts()
                        )
            );
        }else{
            echo json_encode(
                array('message' => 'There is an error.')
            );
        }

        $qqquestions = array();
        $quizModel->partID = $datarow2['part_id'];
        $result3 = $quizModel->readQuestions();
        while($datarow3 = $result3->fetch(PDO::FETCH_ASSOC)){
            array_push($qqquestions, array(
            'question' => $datarow3['question'],
            'question_id' => $datarow3['question_id'],
            'quiz_id' => $datarow3['quiz_id'],
            'part_id' => $datarow3['part_id'],
            'type_id' => $datarow3['type_id'],
            'rightAnswer' => $datarow3['rightAnswer'],
            'choice1' => $datarow3['choice1'],
            'choice2' => $datarow3['choice2'],
            'choice3' => $datarow3['choice3'],
            'choice4' => $datarow3['choice4'],
        ));
        if ($datarow3['type_id']== 1){
          //FOR MULTIPLE CHOICE
          $quizModel->quizID =  $_GET['quizID']+1;
          $quizModel->part_id = $datarow3['part_id']+$_GET['totalParts'];
          $quizModel->question =  $datarow3['question'];
          $quizModel->correct = $datarow3['rightAnswer'];
          array_push($quizModel->values, $datarow3['choice1']);
          array_push($quizModel->values, $datarow3['choice2']);
          array_push($quizModel->values, $datarow3['choice3']);
          array_push($quizModel->values, $datarow3['choice4']);

          if($quizModel->addQuestion()){

              if($quizModel->insertAnswer()){
                  echo json_encode(
                      array(
                          'message' => 'Question added successfully'
                      )
                  );
              }else{
                  echo json_encode(
                      array(
                          'message' => 'There is an error in inserting the correct answer.'
                      )
                  );
              }
              $quizModel->order = 'a';
              $quizModel->values = [];


          }else{
               echo json_encode(
                  array(
                      'message' => 'There is an error in adding questions'
                  )
              );
          }
          }else if($datarow3['type_id'] == 2){
          //FOR TRUE OR FALSE
          $quizModel->quizID = $_GET['quizID']+1;
          $quizModel->part_id = $datarow3['part_id']+$_GET['totalParts'];
          $quizModel->question =  $datarow3['question'];
          $quizModel->correct = $datarow3['rightAnswer'];

             if($quizModel->GenericAddQuestion()){
               if($quizModel->GenericInsertQuestion()){
                     echo json_encode(
                         array(
                             'message' => 'Question added successfully'
                              )
                                 );
                             }else{
                                 echo json_encode(
                                     array(
                                         'message' => 'There is an error in inserting the correct answer.'
                                     )
                                 );
                             }
                         }else{
                              echo json_encode(
                                 array(
                                     'message' => 'There is an error in adding questions'
                                 )
                             );
                         }
            }else if($datarow3['type_id'] == 3){
              $type='Arrange The Sequence';
              }else{
                //FOR GUESS THE WORD
                $quizModel->quizID = $_GET['quizID']+1;
      	        $quizModel->part_id = $datarow3['part_id']+$_GET['totalParts'];
      	        $quizModel->question = $datarow3['question'];
      	        $quizModel->correct = $datarow3['rightAnswer'];

      	        if ($quizModel->GenericAddQuestion()){
      	        	if($quizModel->GenericInsertQuestion()){
      	        		echo json_encode( array('message' => 'Question Added Successfully' ));
      	        	}else{
      	        		echo json_encode( array('message' => 'There is an error in adding correct answer' ));
      	        	}
      	        }else{
      	        	echo json_encode( array('message' => 'There is an error in adding question' ));
      	        }
                }

      }

    array_push($parts_item,$qqquestions);
    array_push($myArray['parts'], $parts_item);
}

echo json_encode($myArray,JSON_PRETTY_PRINT);
?>
