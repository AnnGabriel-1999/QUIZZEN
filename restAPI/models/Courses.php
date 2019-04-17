<?php


    class Courses {
         //Database Properties 
        private $conn;
        private $tblname = "courses";
        private $course_id;
        
        //Constructor   
        public function __construct($db){
            $this->conn = $db;
        }
        
        //Course Properties 
        public $course;
        //public $course_id;
        public $old_course;
        public $errors = array();
        public $foundCourseId;
        
        function validateCourse() {
          if (ctype_alpha(str_replace(' ', '', $this->course)) === false) {
                $this->errors['field'] = "course";
                $this->errors['message'] = "Course name must contain only letters and spaces.";
            }else{
              return true;
            } 
        }
        
        function addCourse(){
            $insertQuery = "INSERT INTO courses 
                                SET
                                  course = :course";
            
            $stmt = $this->conn->prepare($insertQuery);
            $this->course = htmlspecialchars(strip_tags($this->course));
             $stmt->bindParam(':course', $this->course);
            if($stmt->execute()){
                return true;
            }else{
                
                printf("Error: %s".\n, $stmt->err);
                return false;
            }
        }
        
        function editCourse(){
            $updateQuery = "UPDATE courses 
                                SET
                                 course = :course
                                WHERE 
                                 course_id = :course_id";
            $stmt = $this->conn->prepare($updateQuery);
            $this->course = htmlspecialchars(strip_tags($this->course));
            $stmt->bindParam(':course', $this->course);
            $stmt->bindParam(':course_id', $this->course_id);
            if($stmt->execute()){
                return true;
            }else{
                
                printf("Error: %s".\n, $stmt->err);
                return false;
            }
        }
        
        function getCourseID() {
            $query = "SELECT course_id FROM courses
                        WHERE course = ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->old_course);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->course_id = $row['course_id'];
        }
        
        function getAllCourses() {
            $query = "SELECT * FROM courses";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }
        
        function getCoursePrefix(){
            $query = "SELECT * FROM courses WHERE course_id = $this->course_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }
        
        public function setCourseID($course_id){
            $this->course_id = $course_id;
        }

        public function checkIfConvertable($courseString){
        
            $query = "SELECT `course_id` FROM `courses` WHERE `course_prefix` = ?";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $courseString);
            $stmt->execute();

            if($stmt->rowCount() > 0){
                $holder = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($holder);
                $this->foundCourseId = $course_id;
                return true;
            }else{
                return false;
            }
        }
}
