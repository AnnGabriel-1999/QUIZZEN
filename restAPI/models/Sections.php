<?php


    class Sections {
         //Database Properties
        private $conn;

        //Constructor
        public function __construct($db){
            $this->conn = $db;
        }

        //Section properties
        private $section_id;
        private $course_id;
        private $admin_id;
        private $course;
        private $section;
        public $errors = array();
        public $foundSecId;
        private $year_level;
        private $schoolyear_id;



        public function getCourseID() {
            $query = "SELECT course_id FROM courses
                        WHERE course_id = ?";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->course);
            $stmt->execute();

            if($stmt->rowCount()>0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->course_id = $row['course_id'];
                return $this->course_id;
            }else{
                $this->errors['field'] = "course";
                $this->errors['message'] = "Course entered is non existent.";
            }
        }

        public function validateSection() {
            if(!preg_match("/[a-z_\-0-9]/i", $this->section)){
                 $this->errors['field'] = "section";
                 $this->errors['message'] = "Letters, number and hypens are the only characters allowed.";
            }else{
                return true;
            }
        }


        public function getStudentsSections(){

            $query = "SELECT s.student_id, s.section_id, s.fname, s.mname, s.lname, s.status, sec.course_id
             FROM students s INNER JOIN sections sec ON s.section_id = sec.section_id
             WHERE s.section_id = ?";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->section_id);
            $stmt->execute();
            return $stmt;

        }

        public function singleSection(){
            $query = "SELECT s.section, c.course
            FROM sections s INNER JOIN courses c ON s.course_id = c.course_id
            WHERE s.section_id = $this->section_id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }



        public function addSection(){
            $query = "INSERT INTO sections SET course_id = :course_id,
                     admin_id = :admin_id,
                     section = :section";

            $stmt = $this->conn->prepare($query);
            $this->course_id = htmlspecialchars(strip_tags($this->course_id));
            $this->section = htmlspecialchars(strip_tags($this->section));
            $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));
            $stmt->bindParam(':course_id', $this->course_id);
            $stmt->bindParam(':section', $this->section);
            $stmt->bindParam(':admin_id', $this->admin_id);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function updateSection(){
            $query = "UPDATE sections SET
            course_id = :course_id,
            admin_id = :admin_id,
            section = :section WHERE section_id = :section_id";

            $stmt = $this->conn->prepare($query);
            $this->course_id = htmlspecialchars(strip_tags($this->course_id));
            $this->section = htmlspecialchars(strip_tags($this->section));
            $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));
            $this->section_id = htmlspecialchars(strip_tags($this->section_id));
            $stmt->bindParam(':course_id', $this->course_id);
            $stmt->bindParam(':section', $this->section);
            $stmt->bindParam(':admin_id', $this->admin_id);
            $stmt->bindParam(':section_id', $this->section_id);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function setCourseID($course_id){
            $this->course_id = $course_id;
        }

        public function setAdminID($admin_id){
            $this->admin_id = $admin_id;
        }

        public function setCourse($course){
            $this->course = $course;
        }

        public function setSection($sectionName){
            $this->section = $sectionName;
        }

       public function setSectionID($sectionID){
            $this->section_id = $sectionID;
        }

        public function setSchoolYear($schoolyear_id){
            $this->schoolyear_id = $schoolyear_id;
        }

        public function setYearLevel($yearLevel){
            $this->year_level = $yearLevel;
        }

        public function checkIfConvertable($courseString){

            $query = "SELECT a.section_id , a.section FROM sections a left join courses b on a.course_id = b.course_id WHERE a.section = ? ";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $courseString);
            $stmt->execute();

            if($stmt->rowCount() > 0){
                $holder = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($holder);
                $this->foundSecId = $section_id;
                return true;
            }else{
                return false;
            }

        }

        public function viewSectionsByCourse(){
            $query = "SELECT s.section_id, s.section, s.year_level, (select count(*) from students_sections where section_id = s.section_id and schoolyear_id = $this->schoolyear_id) as 'students' FROM sections s 
                      WHERE s.course_id = $this->course_id ORDER BY year_level ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        public function viewSectionsByYearLevel(){
            $query = "SELECT s.section_id, s.section, s.year_level, (select count(*) from students_sections where section_id = s.section_id  and schoolyear_id = $this->schoolyear_id) as 'students' FROM sections s 
                     WHERE s.course_id = $this->course_id and s.year_level = $this->year_level";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        public function viewAssignedProf(){
            $query = "SELECT s.handling_id, a.admin_id, concat(ma.fname, ' ', ma.lname) as 'admin' FROM sections_handled s INNER JOIN admins a on s.admin_id = a.admin_id 
                      INNER JOIN my_admins ma on a.mirror_id = ma.employee_id WHERE s.section_id = $this->section_id   and s.schoolyear_id = $this->schoolyear_id AND s.flag_handle IS NULL";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }


        public function viewVacantProf() {
            $query = "SELECT a.admin_id, concat(ma.fname, ' ', ma.lname) as 'admin' from admins a INNER JOIN my_admins ma on a.mirror_id = ma.employee_id
                      where a.admin_id not IN (SELECT admin_id FROM sections_handled where section_id = $this->section_id and schoolyear_id = $this->schoolyear_id and flag_handle is null)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;        
        }

        public function viewSectionsStudents(){ 
            $query = "SELECT st.student_id, st.fname, st.mname, st.lname, st.status, stc.section_id FROM students st INNER JOIN students_sections stc 
            ON st.student_id = stc.student_id WHERE stc.section_id = $this->section_id and stc.schoolyear_id in (SELECT schoolyear_id FROM school_years where status = 1)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;            
        }

        // public function getHeldSections($adminId){

        //     $query = "SELECT sh.section_id, sec.section FROM sections sec INNER JOIN sections_handled sh 
        //     ON sec.section_id = sh.section_id 
        //     WHERE sh.admin_id = :admin_id and sh.schoolyear_id IN (SELECT schoolyear_id FROM school_years WHERE status = 1)";

        //     $stmt = $this->conn->prepare($query);
        //     $stmt->bindParam(':admin_id', $adminId);
        //     $stmt->execute();
        //     return $stmt; 
        // }

        public function getHandledCourses($admin_id){
            $query = "SELECT DISTINCT a.admin_id, sh.handling_id, sh.section_id, sec.course_id, c.course, c.course_prefix, sy.schoolyear FROM admins a INNER JOIN 
           sections_handled sh on a.admin_id = sh.admin_id INNER JOIN sections sec on sh.section_id = sec.section_id INNER JOIN 
           courses c on sec.course_id = c.course_id 
           INNER JOIN school_years sy on sh.schoolyear_id = sy.schoolyear_id WHERE a.admin_id = ? and 
           sh.schoolyear_id IN (select schoolyear_id FROM school_years where status = 1)";
           $stmt = $this->conn->prepare($query);
           $stmt->bindParam(1, $admin_id);
           $stmt->execute();
           return $stmt;   
         }
 
         public function getHandledSections($admin_id , $course_id){
             $query = "SELECT a.admin_id, sh.section_id, sec.section, (SELECT count(student_id) FROM students_sections 
             where section_id = sh.section_id and sh.schoolyear_id IN (SELECT schoolyear_id from school_years where status = 1)) as 'students', sy.schoolyear , sy.schoolyear_id
             from admins a inner join sections_handled sh on a.admin_id = sh.admin_id
             inner join sections sec on sh.section_id = sec.section_id 
             inner join school_years sy on sh.schoolyear_id = sy.schoolyear_id
             where a.admin_id = :admin_id and sec.course_id = :sec_id and sh.schoolyear_id 
             IN (SELECT schoolyear_id from school_years where status = 1)";
             
             $stmt = $this->conn->prepare($query);
             $stmt->bindParam(':admin_id', $admin_id);
             $stmt->bindParam(':sec_id', $course_id);
             $stmt->execute();
             return $stmt;   
         }

         public function getHandledSectionsByAdmID($admin_id){
            $query = "SELECT a.admin_id, sh.section_id, concat(c.course_prefix, ' ' ,sec.section) as section, (SELECT count(student_id) FROM students_sections 
            where section_id = sh.section_id and sh.schoolyear_id IN (SELECT schoolyear_id from school_years where status = 1)) as 'students', sy.schoolyear , sy.schoolyear_id
            from admins a inner join sections_handled sh on a.admin_id = sh.admin_id
            inner join sections sec on sh.section_id = sec.section_id 
            inner join school_years sy on sh.schoolyear_id = sy.schoolyear_id
            inner join courses c on sec.course_id = c.course_id
            where a.admin_id = :admin_id and sh.schoolyear_id 
            IN (SELECT schoolyear_id from school_years where status = 1)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':admin_id', $admin_id);
            $stmt->execute();
            return $stmt;   
        }
 
    }


