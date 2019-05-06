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

     //Get order from URL
    $users->order = isset($_GET['order']) ? $_GET['order'] : "ASC";

    //Student Query
    $result = $users->getStudents();

   
        
    //Get Row Count of Students
    $rowcount = $result->rowCount();

    if($rowcount>0){
        // Users array
        $users_arr = array();
        $users_arr['data'] = array();
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $user_item = array (
                'student_num' => $student_id,
                'fname' => $fname,
                'mname' => $mname,
                'lname' => $lname, 
                'section' => $section
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