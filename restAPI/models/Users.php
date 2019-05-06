<?php

    class Users {
        //Database Properties
        private $conn;
        private $tblname = "students";

        //Student Properties
        public $client_name;
        public $new_id;
        public $stud_id;
        public $section_id;
        public $section_name;
        public $name;
        public $fname;
        public $mname;
        public $lname;
        public $order;
        public $keyword;
        public $course_id;
        private $student_id;
        private $schoolyear_id;
        //Constructor
        public function __construct($db){
            $this->conn = $db;
        }

        //Get All Students
        public function getStudents() {
            //Create query
            $query = "SELECT s.student_id, s.fname, s.mname, s.lname, c.section from Students s left join sections c on s.section_id = c.section_id
                ORDER BY
                    s.lname $this->order";

            //Prepate Statement
            $stmt = $this->conn->prepare($query);


            //Execute Query
            $stmt->execute();

            return $stmt;
        }

        //Get Single Student
         public function singleStudent() {
            //Create query
            $query = "SELECT s.student_id, s.section_id, s.fname, s.mname, s.lname, s.status, sec.course_id
             FROM students s INNER JOIN sections sec ON s.section_id = sec.section_id
             WHERE s.student_id = ?";

            //Prepate Statement
            $stmt = $this->conn->prepare($query);

            //Bind Student_ID
            $stmt->bindParam(1, $this->stud_id);

            //Execute Query
            $stmt->execute();
            return $stmt;

        }

        public function getStudentSection() {
              //Create Query
            $query = "SELECT section_id FROM sections
                        WHERE
                            section = ?";

            //Prepare Statement
            $stmt = $this->conn->prepare($query);

            //Bind Section Name and Execute
            $stmt->bindParam(1, $this->section_name);
            $stmt->execute();

            //Get Section ID
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->section_id = $row['section_id'];

        }

        //Create
        public function registerStudent() {
            $insertQuery = "INSERT INTO students
                            SET
                              student_id = :student_id,
                              course_id = :course_id,
                              fname = :fname,
                              mname = :mname,
                              lname = :lname
                              ";

            //Prepare Insert Statement
            $stmt = $this->conn->prepare($insertQuery);

            //Clean inputted data
            //$this->section_id = htmlspecialchars(strip_tags($this->section_id));
            $this->course_id = htmlspecialchars(strip_tags($this->course_id));
            //$this->section_name = htmlspecialchars(strip_tags($this->section_id));
            $this->student_id = htmlspecialchars(strip_tags($this->student_id));
            $this->fname = htmlspecialchars(strip_tags($this->fname));
            $this->mname = htmlspecialchars(strip_tags($this->mname));
            $this->lname = htmlspecialchars(strip_tags($this->lname));

            //Bind paramaters
            $stmt->bindParam(':student_id', $this->student_id);
            //$stmt->bindParam(':section_id', $this->section_id);
            $stmt->bindParam(':course_id' , $this->course_id);
            $stmt->bindParam(':fname', $this->fname);
            $stmt->bindParam(':mname', $this->mname);
            $stmt->bindParam(':lname', $this->lname);


            //Execute
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }

        }

        public function verifyStudentID($stud_id) {
            $query = "SELECT student_id FROM students WHERE student_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $stud_id);
            $stmt->execute();
            if($stmt->rowCount()==0){
                return true;
            }else{
                return false;
            }

        }

        //Update
        public function updateStudent() {
            $insertQuery = "UPDATE students
                            SET
                              student_id = :new_id,
                              fname = :fname,
                              mname = :mname,
                              lname = :lname
                            WHERE student_id = :student_id";

            //Prepare Insert Statement
            $stmt = $this->conn->prepare($insertQuery);

            //Clean inputted data
            $this->new_id = htmlspecialchars(strip_tags($this->new_id));
            $this->fname = htmlspecialchars(strip_tags($this->fname));
            $this->mname = htmlspecialchars(strip_tags($this->mname));
            $this->lname = htmlspecialchars(strip_tags($this->lname));

            //Bind paramaters
            $stmt->bindParam(':new_id', $this->new_id);
            $stmt->bindParam(':fname', $this->fname);
            $stmt->bindParam(':mname', $this->mname);
            $stmt->bindParam(':lname', $this->lname);
            $stmt->bindParam(':student_id', $this->student_id);

            //Execute
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }

        }
         public function searchStudent() {
            //Update query
            $query =
             "SELECT
              s.student_id,
              s.fname,
              s.mname,
              s.lname,
              concat(s.fname, ' ', s.mname, ' ', s.lname) as fullname,
              c.section
              FROM
              Students s left join sections c on s.section_id = c.section_id
                  WHERE
                    s.fname LIKE '%$this->keyword%' OR s.lname LIKE '%$this->keyword%'
                    or c.section LIKE '%$this->keyword%' or concat(s.fname, ' ', s.mname, ' ', s.lname) LIKE '%$this->keyword%'";

            $stmt = $this->conn->prepare($query);

            //Execute Query
            $stmt->execute();

            return $stmt;

        }

        public function checkStudId($id){
            $query = "SELECT * FROM `students` WHERE `student_id` = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();

            if($stmt->rowCount() == 0){
                return true;
            }else{
                return false;
            }
        }

        public function setStudentID($studentID){
            $this->student_id = $studentID;
        }

        public function setSchoolYear($schoolYear){
            $this->schoolyear_id = $schoolYear;
        }

        public function getStudentID(){
            return  $this->student_id;
        }

        public function fetchStudentInfo(){
            $query = "SELECT s.student_id, s.fname, s.mname, s.lname, sts.section_id, sec.section, c.course 
                      from students s inner join students_sections sts on s.student_id = sts.student_id
                      inner JOIN sections sec on sts.section_id = sec.section_id 
                      INNER JOIN courses c on sec.course_id = c.course_id 
                      WHERE s.student_id  = $this->student_id AND sts.schoolyear_id =
                      (SELECT schoolyear_id from school_years WHERE status = 1)
                      and s.status = 'UNVERIFIED'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;      
        }

        public function loginStudent($un, $pw){
            $query = "select u.user_id, st.student_id, st.fname, st.lname, u.username, stsec.schoolyear_id, sec.section_id ,sec.section, c.course_id ,c.course FROM students st INNER JOIN user_accounts u ON st.student_id = u.student_id INNER JOIN students_sections stsec ON st.student_id = stsec.student_id INNER JOIN sections sec ON stsec.section_id = sec.section_id 
            INNER JOIN courses c ON sec.course_id = c.course_id WHERE u.username = '$un' AND u.password = '$pw' and 
            stsec.schoolyear_id in (SELECT schoolyear_id FROM school_years WHERE status = 1)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            if( $row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $this->sesClientId = $row['user_id']."-".$row['section_id'];
                $this->client_name = $row['fname']." ".$row['lname'];
                return true;
            }else{
                return false;
            }     

        }

        public function getSingleData(){
            $query = "SELECT st.student_id, st.fname, st.mname, st.lname, sc.section_id, sec.section, c.course, c.course_id FROM students st INNER JOIN students_sections sc on st.student_id = sc.student_id
            INNER JOIN sections sec on sc.section_id = sec.section_id 
            INNER JOIN courses c on sec.course_id = c.course_id WHERE st.student_id = $this->student_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        public function checkIfExisting($emp_id){
          $query = "SELECT * FROM `my_admins` WHERE `employee_id` = :emp_id ";
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(':emp_id', $emp_id);  
          $stmt->execute();  
          if( $row = $stmt->fetch(PDO::FETCH_ASSOC)){
              return true;
          }else{
              return false;
          }
        }
    }
