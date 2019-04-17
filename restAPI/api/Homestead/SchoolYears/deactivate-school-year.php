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

        if ($univ->updateSomething('school_years', 'status', '0', 'schoolyear_id', $_GET['yrid'])) {
            echo json_encode ( array ('message' => 'Success'));
        }else{
            echo json_encode ( array ('error' => 'Error'));
        }
   

  