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

    for ($ctr = 1; $ctr < 5; $ctr++) {
        $res = $univ->selectAll('sections', 'year_level', $ctr, 'course_id', $_GET['courseID']);
        
        $sectionInfo = array(
            'year_level' => $ctr,
            'totalSections' => $res->rowCount()
        );

        array_push($sectionData, $sectionInfo);
    }

    echo json_encode ($sectionData);

    