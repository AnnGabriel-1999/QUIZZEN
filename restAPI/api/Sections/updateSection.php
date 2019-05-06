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

    $data = json_decode(file_get_contents('php://input'));

    $sections->setCourseID($data->course_id);
    $sections->setAdminID($data->admin_id);
    $sections->setSectionID($data->section_id);  
    $sections->setSection($data->section);  

    if($sections->updateSection()){
        echo json_encode(array('success' => 'Section updated.'));
    }else{
        echo json_encode(array('error' => 'Section update failed.'));
    }