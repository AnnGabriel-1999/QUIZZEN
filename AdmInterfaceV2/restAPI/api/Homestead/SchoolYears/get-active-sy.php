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


    $res = $univ->selectAll2('school_years', 'status', '1');
    
    if ($res->rowCount() > 0) {
       while($row = $res->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $schoolYearInfo = array(
                'schoolyear_id' => $schoolyear_id,
                'schoolyear' => $schoolYear,
                'semester' => $semester
            );

            array_push($schoolYearData, $schoolYearInfo);
       }
        

        echo json_encode ($schoolYearData);
    }else{
        echo json_encode (array(
            'message' => 'no active schoolyear'
        ));
    }
       

    