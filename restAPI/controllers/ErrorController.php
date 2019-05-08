<?php

    class ErrorController {
        
        public $errors = array();
        public $name;
        
        
        public function UpStudentFields($student_id, $section_name, $fname, $lname){
            if($student_id == ""){
                $this->errors['field'] = "Student ID";
                $this->errors['message'] = "All fields are required";
            }elseif($section_name == ""){
                $this->errors['field'] = "Section Name";
                $this->errors['message'] = "All fields are required";
            }elseif($fname == ""){
                $this->errors['field'] = "First Name";
                $this->errors['message'] = "All fields are required";
            }elseif($lname == ""){
                $this->errors['field'] = "Last Name";
                $this->errors['message'] = "All fields are required";
            }else{
                return true;
            }
        }
        
        public function validateStudentID($student_id, $field){

            if (!preg_match('/^[0-9]*$/', $student_id)) {
                $this->errors['field'] = $field;
                $this->errors['message'] = "Student ID must be numbers only";
            }elseif(strlen($student_id)>10){
                $this->errors['field'] = $field;
                $this->errors['message'] = "Student ID must only contain 10 numbers";
            }else{
                return true;
            }

        }
        
        public function validateEmpID($empID, $field){

            if(strlen($empID)>10){
                $this->errors['field'] = $field;
                $this->errors['message'] = "Employee ID must only contain 10 numbers";
            }else{
                return true;
            }

        }
        
        public function validateName($fname, $mname, $lname) {
            if (!preg_match("/^[a-zA-Z ]*$/",$fname)) {
                $this->errors['field'] = "First Name";
            }elseif(!preg_match("/^[a-zA-Z ]*$/",$mname)) {
                $this->errors['field'] = "Middle Name";
            }elseif(!preg_match("/^[a-zA-Z ]*$/",$lname)) {
                $this->errors['field'] = "Last Name";
            }
            
            if($this->errors['field'] != null) {
                $this->errors['message'] = "Name must only contain letters";
            }else{
                return true;
            }
        }
        
        public function numbersOnly($string, $field){
            if(is_numeric($string)){
                return true;
            }else{
                $this->errors['field'] = $field;
                $this->errors['message'] = "Numbers only";
            }
        }
        
        public function checkField($string, $field,$min_len,$max_len){
            if($string == "" || ctype_space($string)) {
                $this->errors['field'] = $field;
                $this->errors['message'] = "All fields are required";
            }elseif(strlen($string) < $min_len){
                $this->errors['field'] = $field;
                $this->errors['message'] = $field." length has a minimum of ".$min_len;
            }elseif(strlen($string) >= $max_len){
                $this->errors['field'] = $field;
                $this->errors['message'] = $field." length must be maximum of ".$max_len;
            }else{
                return true;
            }
        }

        public function checkHaveSpecialChar($checkee,$field){
             if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $checkee)){
                $this->errors['field'] = $field;
                $this->errors['message'] = $field." has special character ";
            }else{
                return true;
            }
        }

        public function checkIfMatch($checkee1,$checkee2,$field){
            if( $checkee1 != $checkee2 ){
                $this->errors['field'] = $field;
                $this->errors['message'] = $field ." does not match";
            }else{
                return true;
            }
        }

        public function checkExtension($filename,$desiredExt){
            $filenameArr = explode('.', $filename);
            if($filenameArr[1] == $desiredExt){
                return true;
            }else{
                return false;
            }
        }

        public function checkCSVFormat($datarow,$rowlen){
            
            $errorDetect = false;
            
            if (count($datarow) == $rowlen) {
                for($x=0; $x<=count($datarow)-1; $x++){ // pangcheck kung may blank
                    if($datarow[$x] == ''){
                        $errorDetect = true;
                    }
                }
            }else{
                $errorDetect = true;
            }

            echo $errorDetect;

            if($errorDetect){
                return false;
            }else{
                return true;
            }

        }

        public function checkStudIDCSV($student_id){
            if (preg_match('/^\d{10}$/', $student_id)) {
                return true;
            } else {
                return false;
            }
        }

        public function checkFormat($csv,$row){
            
            $errorDetect = false;
            $datacounter = 0;
            
            while ($datarow = fgetcsv($csv)) { // pangcheck kung may ibang field na di provided
                $datacounter++;
                if(count($datarow) != $row){
                    $errorDetect = true;
                }
                for($x=0; $x<=count($datarow)-1; $x++){ // pangcheck kung may blank
                    if($datarow[$x] == ''){
                        $errorDetect = true;
                    }
                }
            }
            if(!$errorDetect && $datacounter > 1){
                return true;
            }else{
                return false;
            }
        }

    }