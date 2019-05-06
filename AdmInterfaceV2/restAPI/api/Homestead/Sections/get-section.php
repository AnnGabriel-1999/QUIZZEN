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
    $sectionData = array();

    $sectionID = $_GET['section_id'];
    
    $res = $univ->selectAll2('sections', 'section_id', $sectionID);
    

    if($res->rowCount() > 0) {
        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $sectionInfo = array(
                'section_id' => $section_id,
                'section' => $section,
                'year_level' => $year_level
            );
            array_push($sectionData, $sectionInfo);
        }
        echo json_encode ($sectionData); 
    }else{
        echo json_encode(array('message'=>'No result'));
    }

  