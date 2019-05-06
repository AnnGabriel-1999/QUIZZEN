<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';
    include_once '../../models/Universal.php';
    include_once '../../controllers/ErrorController.php';

    // Instantiate Classes
    $database = new Database();
    $db = $database->connect();
    $quiz = new Quiz($db);
    $univ = new Universal($db);
    $errorCont = new ErrorController();
    $counter = 0;

    // Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

     if($errorCont->checkField($_POST['quizTitle'], 'Quiz Title', 5, 250)){
        if($errorCont->checkField($_POST['description'], 'Quiz Description', 10, 250)){
            if(isset($_FILES['file']['tmp_name'])) {
                $filepath = '../../../AdmInterfaceV2/uploads/quiz/'.$_FILES['file']['name'];
                $quiz->filepath = $filepath;
            }
                $quiz->quizTitle = $_POST['quizTitle'];
                $quiz->description =  $_POST['description'];
                $quiz->admin_id = $_POST['admin_id'];
                $quiz->part_type =  $_POST['part_type'];
                $quiz->passingrate =  $_POST['passingrate'];
                if($_POST['tags'] != ""){
                    $quiz->tags = explode(',', $_POST['tags']);
                }

                //add tags
                if(!empty($quiz->tags)){
                    foreach($quiz->tags as $tag){
                        $res = $univ->selectAll('quiz_tags', 'admin_id', $_POST['admin_id'], 'tag_name', $tag);
                        if($res->rowCount() <= 0){
                            $univ->insert2('quiz_tags', 'admin_id', 'tag_name', $_POST['admin_id'], $tag);
                        }
                    }
                }
                if ($quiz->addQuiz()) {
                    echo json_encode(array('success' => 'Quiz created successfully!'));
                    if(isset($_FILES['file']['tmp_name'])) {
                        move_uploaded_file($_FILES['file']['tmp_name'], $filepath);
                    }
                } else {
                    echo json_encode(array('error' => 'Failed to create quiz!'));
                }

               if(!empty($quiz->tags)) {
                    foreach($quiz->tags as $tag){
                        $res = $univ->selectAll('quiz_tags', 'admin_id', $_POST['admin_id'], 'tag_name', $tag);
                        while($row = $res->fetch(PDO::FETCH_ASSOC)){
                            extract($row);
                            $univ->insert2WithSubquery('quiz_tag_colllections', 'tag_id', 'quiz_id', $tag_id, '(select max(quiz_id) from quizzes)');
                        }
                    }    
                }
                
        }   
    }

    if($errorCont->errors != null) {
        echo json_encode (
            $errorCont->errors  
        );
    }