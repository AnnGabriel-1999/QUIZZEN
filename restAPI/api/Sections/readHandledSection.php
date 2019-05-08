<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Sections.php';

    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();

    //Instantiate Quiz Class
    $sections = new Sections($db); 

    //query to return yung mga handled kong sections

    $result = $sections->getHeldSections($_GET['adminId']);
    $rowCount = $result->rowCount();
    $data_arr = array();

    if($rowCount > 0){
        while($datarow = $result->fetch(PDO::FETCH_ASSOC)){
            extract($datarow);
            $section = array (
                'section_id' => $section_id,
                'section' => $section
            );
            array_push($data_arr,$section);
        }

        echo json_encode($data_arr);
    }else{
        echo json_encode(array('error' => 'No Sections Assigned.'));
    }