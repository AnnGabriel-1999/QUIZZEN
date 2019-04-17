<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Users.php';

    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();

    //Instantiate Users Class
    $users = new Users($db); 

    $users->keyword =  $_GET['key'];


    //Get Post
    $result = $users->searchStudent();

   //Get Row Count of Students
    $num = $result->rowCount();
   
    if($num>0){
        // Users array
        $users_arr = array();
        $users_arr['data'] = array();
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            
            $user_item = array (
                'student_num' => $row['student_id'],
                'fname' => $row['fname'],
                'mname' => $row['mname'],
                'lname' => $row['lname'], 
                'fullname' => $row['fullname'],
                'section' => $row['section'],
                'key' => $users->keyword
            );

            //Push to data array 
            array_push($users_arr['data'], $user_item);
        }
        
        //Convert to JSON
        echo json_encode($users_arr);
    }else{
        // No students
        echo json_encode(array(
            'message' => 'No students enrolled to quizzen yet.'
        ));
    }