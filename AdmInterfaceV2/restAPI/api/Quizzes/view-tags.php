<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    include_once '../../config/Database.php';
    include_once '../../models/Universal.php';
    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    //Instantiate Quiz Class
    $univ = new Universal($db);
 
    $admin_id = $_GET['admin_id'];

    $result = $univ->selectAll2('quiz_tags', 'admin_id', $admin_id);

    if($result->rowCount() > 0) {
        $tags_arr['data'] = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $tag_item = array(
                'tag_name' => $tag_name,
                'tag_id' => $tag_id,
                'date_created' => $date_created
            );
            array_push($tags_arr['data'], $tag_item);
        }
        echo json_encode($tags_arr['data']);
    }else{
        echo json_encode(array('message' => 'No tags'));
    }