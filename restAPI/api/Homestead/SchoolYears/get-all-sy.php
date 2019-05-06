<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Universal.php';
    include_once '../../../controllers/ErrorController.php';
    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    //Instantiate Users Class
    $univ = new Universal($db);
    $schoolYearData = array();


    $res = $univ->getAllSY();
    
    if ($res->rowCount() > 0) {
       while($row = $res->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            if($semester == 1){
                $semester = "1st Semester";
            }elseif($semester == 2){
                $semester = "2nd Semester";
            }else{
                $semester = "Midyear";
            }

            if($status==1){
                $status1 = true;
            }else{
                $status1 = false;
            }

            $schoolYearInfo = array(
                'schoolyear_id' => $schoolyear_id,
                'schoolyear' => $schoolYear,
                'semester' => $semester,
                'total' => $total,
                'st' => $status1
             
            );

            array_push($schoolYearData, $schoolYearInfo);
       }
        

        echo json_encode ($schoolYearData);

    }else{
        echo json_encode (array(
            'message' => 'no active schoolyear'
        ));
    }
       

    