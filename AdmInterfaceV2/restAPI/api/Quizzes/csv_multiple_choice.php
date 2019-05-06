<?php
include_once '../../config/Database.php';
include_once '../../models/Quiz.php';
include_once '../../controllers/ErrorController.php';
include_once '../../models/Courses.php';
include_once '../../models/Sections.php';
include_once '../../models/Users.php';
include_once '../../models/Universal.php';

$database = new Database();
$db = $database->connect();
$quiz = new Quiz($db);
$courseModel = new Courses($db);
$sectionModel = new Sections($db); 
$userModel = new Users($db);
$univModel = new Universal($db);
$errorCont = new ErrorController();

$firstlineSkipper = 0;
$dataCounter = 0;
$success = 0;

if (isset($_FILES['multiple']['tmp_name'])){
	
	if($errorCont->checkExtension($_FILES['multiple']['name'],'csv')){ // CHECK IF UPLOADED FILE IS CSV
		$handle = fopen($_FILES['multiple']['tmp_name'], 'r');
		if($errorCont->checkFormat($handle,6)){
			$handle2 = fopen($_FILES['multiple']['tmp_name'], 'r');
			while ($data = fgetcsv($handle2)) {
				$firstlineSkipper++;
				if($firstlineSkipper > 1){
					$dataCounter++;
					//EXECUTE SUCCESS
					$quiz->quizID = $_POST['quiz_id'];
                    $quiz->part_id = $_POST['part_id'];
                    $quiz->question = $data[0];
                    
                    array_push($quiz->values, $data[1]);
                    array_push($quiz->values, $data[2]);
                    array_push($quiz->values, $data[3]);
                    array_push($quiz->values, $data[4]);

                    $quiz->correct = in_array($data[5], $quiz->values) ? $data[5] : $data[1];

		        	if($quiz->addQuestion()){
                        if($quiz->insertAnswer()){
                        	$success++;
                        }else{
                        	$quiz->deleteFrequentQ();
                        }
                    }
                    $quiz->values = [];
                    $quiz->order = 'a';
				}
			}
			if($success == $dataCounter){
				echo json_encode( array('success' => 'CSV FILE SUCCESSFULLY ADDED!'));
			}else{
				echo json_encode( array('error' => 'Failed Uploading of Files'));
			}
		}else{
			echo json_encode( array('error' => 'Your File is Unreadable try downloading our format.'));
			fclose($handle);
		}
	}else{
		echo json_encode( array('error' => 'Please Upload CSV Files Only.'));
	}
}elseif (isset($_FILES['TorF']['tmp_name'])) {
	
	if($errorCont->checkExtension($_FILES['TorF']['name'],'csv')){ // CHECK IF UPLOADED FILE IS CSV
		$handle = fopen($_FILES['TorF']['tmp_name'], 'r');
		if($errorCont->checkFormat($handle,2)){
			$handle2 = fopen($_FILES['TorF']['tmp_name'], 'r');
			while ($data = fgetcsv($handle2)) {
				$firstlineSkipper++;
				if($firstlineSkipper > 1){
					$dataCounter++;
					//EXECUTE SUCCESS
					$quiz->quizID = $_POST['quiz_id'];
		        	$quiz->part_id = $_POST['part_id'];
		        	$quiz->question = $data[0];

		        	if($data[1] != 'TRUE' || $data[1] != 'FALSE'){
		        		$quiz->correct = 'TRUE';
		        	}else{
		        		$quiz->correct = $data[1];
		        	}


		        	if($quiz->GenericAddQuestion()){
                        if($quiz->GenericInsertQuestion()){
                        	$success++;
                        }
                    }
				}
			}

			if($success == $dataCounter){
				echo json_encode( array('success' => 'CSV FILE SUCCESSFULLY ADDED!'));
			}else{
				echo json_encode( array('error' => 'Failed Uploading of Files'));
			}
		}else{
			echo json_encode( array('error' => 'Your File is Unreadable try downloading our format.'));
			fclose($handle);
		}
	}else{
		echo json_encode( array('error' => 'Please Upload CSV Files Only.'));
	}
}elseif (isset($_FILES['GTW']['tmp_name'])) {
	if($errorCont->checkExtension($_FILES['GTW']['name'],'csv')){ // CHECK IF UPLOADED FILE IS CSV
		$handle = fopen($_FILES['GTW']['tmp_name'], 'r');
		if($errorCont->checkFormat($handle,2)){
			$handle2 = fopen($_FILES['GTW']['tmp_name'], 'r');
			while ($data = fgetcsv($handle2)) {
				$firstlineSkipper++;
				if($firstlineSkipper > 1){
					$dataCounter++;
					//EXECUTE SUCCESS
					$quiz->quizID = $_POST['quiz_id'];
		        	$quiz->part_id = $_POST['part_id'];
		        	$quiz->question = $data[0];
		        	$quiz->correct = $data[1];

		        	if($quiz->GenericAddQuestion()){
                        if($quiz->GenericInsertQuestion()){
                        	$success++;
                        }
                    }
				}
			}

			if($success == $dataCounter){
				echo json_encode( array('success' => 'CSV FILE SUCCESSFULLY ADDED!'));
			}else{
				echo json_encode( array('error' => 'Failed Uploading of Files'));
			}
		}else{
			echo json_encode( array('error' => 'Your File is Unreadable try downloading our format.'));
			fclose($handle);
		}
	}else{
		echo json_encode( array('error' => 'Please Upload CSV Files Only.'));
	}
}elseif (isset($_FILES['arrange']['tmp_name'])) {
	if($errorCont->checkExtension($_FILES['arrange']['name'],'csv')){ // CHECK IF UPLOADED FILE IS CSV
		$handle = fopen($_FILES['arrange']['tmp_name'], 'r');
		if($errorCont->checkFormat($handle,5)){
			$handle2 = fopen($_FILES['arrange']['tmp_name'], 'r');
			while ($data = fgetcsv($handle2)) {
				$firstlineSkipper++;
				if($firstlineSkipper > 1){
					$dataCounter++;
					//EXECUTE SUCCESS
					$quiz->quizID = $_POST['quiz_id'];
                    $quiz->part_id = $_POST['part_id'];
                    $quiz->question = $data[0];
                    
                    array_push($quiz->values, $data[1]);
                    array_push($quiz->values, $data[2]);
                    array_push($quiz->values, $data[3]);
                    array_push($quiz->values, $data[4]);

		        	if($quiz->addQuestion()){
                        $success++;
                    }
                    $quiz->values = [];
                    $quiz->order = 'a';
				}
			}
			if($success == $dataCounter){
				echo json_encode( array('success' => 'CSV FILE SUCCESSFULLY ADDED!'));
			}else{
				echo json_encode( array('error' => 'Failed Uploading of Files'));
			}
		}else{
			echo json_encode( array('error' => 'Your File is Unreadable try downloading our format.'));
			fclose($handle);
		}
	}else{
		echo json_encode( array('error' => 'Please Upload CSV Files Only.'));
	}
}elseif (isset($_FILES['students']['tmp_name'])) {
	$rejects = array();
	$rejects['missingData'] = array();
	$rejects['wrongInput'] = array();
	$rejects['duplicate'] = array();
	
	if($errorCont->checkExtension($_FILES['students']['name'],'csv')){ // CHECK IF UPLOADED FILE IS CSV

			$handle2 = fopen($_FILES['students']['tmp_name'], 'r');
				//habang may nakukuha kang line tol
				while ($datarow = fgetcsv($handle2)) {
					
					$dataCounter++;
					//pangskip lang ng title pare
					if($dataCounter>1){
						//tatanong mo ngayon kung kumpleto per line pag hindi lagay mo sa errors
						if($errorCont->checkCSVFormat($datarow,4)){

							if($errorCont->checkStudIDCSV($datarow[0])){
								if($userModel->checkStudId($datarow[0])){ //check lang if may nag eexist nang student na ganto sa data base

									$userModel->setStudentID($datarow[0]);

									$userModel->section_id =$_POST['section_id'];
									$userModel->course_id = $_POST['courseID'];

									$userModel->fname = $datarow[1];
									$userModel->mname = $datarow[2];
									$userModel->lname = $datarow[3];

									$userModel->registerStudent();

									$univModel->insert3('students_sections', 'student_id', 'section_id', 'schoolyear_id', $userModel->getStudentID(), 
										$userModel->section_id, $_POST['schoolyear_id']);
									$success++;
									
								}else{
									array_push($rejects['duplicate'],$dataCounter);
								}
							}else{
								array_push($rejects['wrongInput'],$dataCounter);
							}
						}else{
							array_push($rejects['missingData'],$dataCounter);
						}
					
					}
				}
				fclose($handle2);

	}else{
		echo json_encode( array('error' => 'Please Upload CSV Files Only.'));
	}

	//pagka may error
	if( $rejects['missingData'] != null || $rejects['wrongInput'] != null || $rejects['duplicate'] != null ){ // check kung may nag error na data
		echo json_encode($rejects ,JSON_PRETTY_PRINT);
	}
	//pagka pasok lahat
	if( $dataCounter-1 == $success && $success != 0){
		echo json_encode( array('success' => 'All Students Uploaded Successful'));
	}
	//pagka walang laman
	if($dataCounter <= 1){
		echo json_encode( array('error' => 'Your CSV File is unreadable try downloading our format'));
	}
}elseif (isset($_FILES['admins']['tmp_name'])) {
	$rejects = array();
	$rejects['missingData'] = array();
	$rejects['wrongInput'] = array();
	$rejects['duplicate'] = array();
	
	if($errorCont->checkExtension($_FILES['admins']['name'],'csv')){ // CHECK IF UPLOADED FILE IS CSV

			$handle2 = fopen($_FILES['admins']['tmp_name'], 'r');
				//habang may nakukuha kang line tol
				while ($datarow = fgetcsv($handle2)) {
					
					$dataCounter++;
					//pangskip lang ng title pare
					if($dataCounter>1){
						//tatanong mo ngayon kung kumpleto per line pag hindi lagay mo sa errors
						if($errorCont->checkCSVFormat($datarow,4)){

							if($errorCont->checkStudIDCSV($datarow[0])){
								if(!$userModel->checkIfExisting($datarow[0])){ //check lang if may nag eexist nang student na ganto sa data base

									if($univModel->insert5('my_admins', 'employee_id' ,'fname', 'mname', 'lname', 'status', $datarow[0], $datarow[1], $datarow[2], $datarow[3], 'false')){
                           				$success++;
                        			}
									
								}else{
									array_push($rejects['duplicate'],$dataCounter);
								}
							}else{
								array_push($rejects['wrongInput'],$dataCounter);
							}
						}else{
							array_push($rejects['missingData'],$dataCounter);
						}
					
					}
				}
				fclose($handle2);

	}else{
		echo json_encode( array('error' => 'Please Upload CSV Files Only.'));
	}

	//pagka may error
	if( $rejects['missingData'] != null || $rejects['wrongInput'] != null || $rejects['duplicate'] != null ){ // check kung may nag error na data
		echo json_encode($rejects ,JSON_PRETTY_PRINT);
	}
	//pagka pasok lahat
	if( $dataCounter-1 == $success && $success != 0){
		echo json_encode( array('success' => 'All Students Uploaded Successful'));
	}
	//pagka walang laman
	if($dataCounter <= 1){
		echo json_encode( array('error' => 'Your CSV File is unreadable try downloading our format'));
	}
}

?>