<?php
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
   $dateInfo = array();
   $countInfo = array();
   $ctr = 0;
   $quizLen = 0;

   date_default_timezone_set('Asia/Manila');
   $res = $univ->selectAll2('quizzes', 'admin_id', $_GET['adminID']);

   if($res->rowCount() > 0){
        while($row = $res->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $date = array(
                'date' => $delete_date,
                'quizid' => $quiz_id
            );
            array_push($dateInfo, $date);
        }

        foreach($dateInfo as $dateObj){
            $raw =  date("Y-m-d");
            
            if($dateObj['date'] != ""){
                $min = $dateObj['date'];
                $diff = date_diff(date_create($raw), date_create($min));
                $counts = array('diff' => $diff->d, 'quiz_id' => $dateObj['quizid']);
                array_push($countInfo, $counts);
            }else{
                continue;
            }
        }

        foreach($countInfo as $countObj){
            if($countObj['diff'] >= 7) {
                $quizLen++;
            }
        }
         
   
        foreach($countInfo as $countObj){
            if($countObj['diff']>=7){
                if($univ->delete1('user_answers', 'quiz_id', $countObj['quiz_id'])){
                    if($univ->delete1('quiz_takers', 'quiz_id', $countObj['quiz_id'])){
                        if($univ->delete1('quiz_tag_colllections', 'quiz_id', $countObj['quiz_id'])){
                            if($univ->delete1('quiz_parts', 'quiz_id', $countObj['quiz_id'])){
                                if($univ->delete1('answer_choices', 'quiz_id', $countObj['quiz_id'])){
                                    if($univ->delete1('questions', 'quiz_id', $countObj['quiz_id'])){
                                        if($univ->delete1('quizzes', 'quiz_id', $countObj['quiz_id'])){
                                            $ctr+=1;
                                        }else{
                                            echo json_encode(array('error' => '@questions tbl'));
                                        }
                                    }else{
                                        echo json_encode(array('error' => '@questions tbl'));
                                    }
                                }else{
                                    echo json_encode(array('error' => '@answer_choices tbl'));
                                }
                            }else{
                                echo json_encode(array('error' => '@quiz_parts tbl'));
                            }
                        }else{
                            echo json_encode(array('error' => '@quiz_tag_colllections tbl'));
                        }
                    }else{
                        echo json_encode(array('error' => '@quiz_takers tbl'));
                    }
                }else{
                    echo json_encode(array('error' => '@user_answers tbl'));
                }
            }
        }

        if($quizLen==$ctr){
            echo json_encode(array('success' => 'nice'));
        }else{
            echo json_encode(array('error' => 'hmmm'));
        }
       
   }else{
       echo json_encode(array('error' => 'no quiz'));
   }
