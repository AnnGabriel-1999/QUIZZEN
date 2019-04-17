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
    $result = $sections->getHandledSections($_GET['admin_id'] , $_GET['course_id']);
    $rowcount = $result->rowCount();
    
    $sectionsArr = array();

    if($rowcount > 0){
        while ($datarow = $result->fetch(PDO::FETCH_ASSOC)){
            $sectionData = array(
                'admin_id' => $datarow['admin_id'],
                'section_id' => $datarow['section_id'],
                'section' => $datarow['section'],
                'students' => $datarow['students'],
                'schoolyear' => $datarow['schoolyear'],
                'schoolyear_id' => $datarow['schoolyear_id']
            );
            array_push($sectionsArr, $sectionData);
        }

        echo json_encode($sectionsArr);
    }else{
        echo json_encode(array('error' => 'No Handled Sections' ));
    }

?>