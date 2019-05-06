<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Universal.php';
    include_once '../../../models/Sections.php';
    include_once '../../../controllers/ErrorController.php';
    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    //Instantiate Users Class
    $univ = new Universal($db);
    $secs = new Sections($db);
    $sectionData = array();
    $secs->setSectionID($_GET['section_id']);
    $secs->setSchoolYear($_GET['syrid']);
    $assignedData = array();

    $res = $secs->viewVacantProf();

    if ($res->rowCount() > 0) {
         while ($row = $res->fetch(PDO::FETCH_ASSOC)){
             extract($row);
             $assignedInfo = array(
                 'admin_id' => $admin_id,
                 'name' => $admin
             );
             array_push($assignedData, $assignedInfo);
         }

         echo json_encode ($assignedData);
    }else{
        echo json_encode (
            array (
                'message' => 'No vacant prof'
            )
        );
    }