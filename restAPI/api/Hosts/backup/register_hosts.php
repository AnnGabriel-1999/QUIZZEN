<?php
    //Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Hosts.php';
    include_once '../../models/Universal.php';
    include_once '../../controllers/ErrorController.php';
    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    //Instantiate Users Class
    $hosts = new Hosts($db);
    $univ = new Universal($db);
    //Instatiate Error Controller
    $errorCont = new ErrorController();
    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));
    
  
    // if($errorCont->checkField($data->fname, 'Firstname' , 1 , 100)){ 
    //     if($errorCont->checkField($data->lname, 'Lastname' , 1 , 100)){ 
            if($errorCont->checkHaveSpecialChar($data->username,"Host Username")){
                    if($errorCont->checkIfMatch($data->password,$data->confirm_pw,"Password")){ 
                        if($errorCont->checkField($data->password, 'Password', 10 , 20)){ 
                            if($errorCont->checkField($data->confirm_pw, 'Retype Password', 10 , 20)){ 
                                // $hosts->fname = $data->fname;
                                // $hosts->mname = $data->mname;
                                 $hosts->mirror_id = $data->mirror_id;
                                $hosts->username = $data->username;
                                $hosts->password =  $data->password;

                                if($hosts->registerHost()){
                                    if($univ->updateSomething('my_admins', 'status', 'true', 'employee_id', $data->mirror_id)){
                                        echo json_encode(array('success' => 'Host Registration Success.'));
                                    }else{
                                        echo json_encode(array('error' => 'There is an error.'));
                                    }
                                   
                                }else{
                                    echo json_encode(array('message' => 'Host Registration Failed.'));
                                }
                        }
                    }
                }
            }
       // }   
 //   }

    if ($errorCont->errors != null) {
        echo json_encode($errorCont->errors);
    }

    ?>


    