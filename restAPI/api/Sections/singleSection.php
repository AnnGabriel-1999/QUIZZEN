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
    $sections->setSectionID($_GET['section_id']);
    $result = $sections->singleSection();
    $rowcount = $result->rowCount();

    if ($rowcount > 0) {
        // Quiz array
        $sec_arr['data'] = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $section = array(
                'section' => $section,
                'course' => $course
            );

            // Push to data array
            array_push($sec_arr['data'], $section);
        }

        //Convert to JSON
        echo json_encode($sec_arr['data']);
    }