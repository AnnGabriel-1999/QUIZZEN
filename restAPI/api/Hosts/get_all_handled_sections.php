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
    $result = $sections->getHandledSectionsByAdmID($_GET['admin_id']);
    $rowcount = $result->rowCount();
    
    $sectionsArr = array();

    if($rowcount > 0){
        while ($datarow = $result->fetch(PDO::FETCH_ASSOC)){
            $sectionData = array(
                'section_id' => $datarow['section_id'],
                'section' => $datarow['section']
            );
            array_push($sectionsArr, $sectionData);
        }

        echo json_encode($sectionsArr);
    }else{
        echo json_encode(array('error' => 'No Handled Sections' ));
    }

?>