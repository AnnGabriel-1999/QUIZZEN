<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Quiz.php';

    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();

    //Instantiate Quiz Class
    $quiz = new Quiz($db);

    //Get ID from URL
   // $quiz->employee_id=$_GET['empID'];

    //Get Post
    $result = $quiz->singleEmployee();

    $myArray['handle'] = array();
    while($datarow = $result->fetch(PDO::FETCH_OBJ)){ 
    $quiz_item = array (
                'emp_id' =>$datarow->mirror_id,
                'emp_name' =>$datarow->fullname,
                'sec_id' =>$datarow->section_id,
                'section' =>$datarow->section
            );
        /*foreach($datarow['section_id'] as $sss){
            array_push($section, $sss);
        }*/
        /*$section = array();
        while($datarow2 = $result->fetch(PDO::FETCH_ASSOC)){
            array_push($section, array(
            'section' => $datarow2['section']
        ));
    }*/
        
       // array_push($quiz_item,$section);
        array_push($myArray['handle'], $quiz_item);
    }
        echo json_encode($myArray,JSON_PRETTY_PRINT);
?>