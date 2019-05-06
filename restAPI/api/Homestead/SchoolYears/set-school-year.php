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

    $res = $univ->selectAll2('school_years', 'status', '1');

    if ($res->rowCount() == 1) {
        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            if($univ->updateSomething('school_years', 'status', '0', 'schoolyear_id', $schoolyear_id)){
                if ($univ->updateSomething('school_years', 'status', '1', 'schoolyear_id', $_GET['yrid'])) {
                    echo json_encode ( array ('message' => 'Success'));
                }else{
                    echo json_encode ( array ('error' => 'Error'));
                }
            }
        }
    }else{
        if ($univ->updateSomething('school_years', 'status', '1', 'schoolyear_id', $_GET['yrid'])) {
            echo json_encode ( array ('message' => 'Success'));
        }else{
            echo json_encode ( array ('error' => 'Error'));
        }
    }

  