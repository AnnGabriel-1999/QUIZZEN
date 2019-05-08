<?php

    Class Quiz {
        // Database Properties
        private $conn;
        private $tblname1 = "quiz";
        private $tblname2 = "hosted_quizzes";

        // Quiz Properties
        public $quizID;
        public $quizTitle;
        public $parts;
        public $hosted_id;
        public $admin_id;
        public $fname;
        public $date_created;
        public $kunware_session;
        public $description;
        public $filepath;
        public $totalQuiz;
        public $MaxID;
        public $lastQId;

        //Quiz Part Properties
        public $type_id;
        public $type_name;
        public $part_title;
        public $position;
        public $totalParts;
        public $duration;
        public $key;
        public $tags = array();
        public $choices_keys = array();
        public $part_type;

        //Question Properties
        public $question;
        public $question_id;
        public $answer_id;
        public $values = array();
        public $correct;
        public $order = 'a';


        //Quiz Update Variables
        public $new_part_title;
        public $new_type_id;
        public $part_id;
        public $kind_part;

        private $section_id;

        public $partID;


        // Constructor
        public function __construct($db) {
            $this->conn = $db;
        }
        
         public function accountUser(){
      $query = " SELECT CONCAT(fname,' ', LEFT(mname,1),'.',' ', lname) as fullname 
      FROM my_admins a LEFT JOIN admins b ON a.employee_id = b.mirror_id WHERE b.admin_id = $this->admin_id";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
    }  
      public function homesteadUser(){
      $query = " SELECT username from superadmin WHERE id = $this->admin_id";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
    }     
        
        //Read Questions
       public function readQuestions() {
           //Create query
       $query = " SELECT
                   a.question,
                   a.question_id,
                   b.quiz_id,
                   a.part_id,
                   d.type_id,
                   a.filepath,
                   (
                       SELECT value from answer_choices
                       WHERE a.answer = choice_id
                   )as rightAnswer,
                   (
                       SELECT
                       value
                       from answer_choices
                       WHERE
                       a.question_id = question_id
                       AND post = 'a' OR 'A'
                   )as choice1,
                    (
                       SELECT value
                       from answer_choices
                       WHERE
                       a.question_id = question_id
                       AND post = 'b' OR 'B'
                   )as choice2,
                    (
                       SELECT value
                       from answer_choices
                       WHERE
                       a.question_id = question_id
                       AND post = 'c' OR 'C'
                   )as choice3,
                    (
                       SELECT value
                       from answer_choices
                       WHERE
                       a.question_id = question_id
                       AND post = 'd' OR 'D'
                   )as choice4
                   FROM
                   questions a,
                   quizzes b,
                   admins c,
                   quiz_parts d
                   WHERE
                   c.admin_id = b.admin_id
                   AND
                   b.quiz_id = a.quiz_id
                   AND
                 d.part_id = a.part_id
                  AND
                  a.part_id = $this->partID";

           //Prepare Statement
           $stmt = $this->conn->prepare($query);

           //Execute Query
           $stmt->execute();

           return $stmt;
       }

        //Read Questions
       public function publishedSegment() {
           //Create query
       $query = " SELECT
                   a.question,
                   a.question_id,
                   b.quiz_id,
                   a.part_id,
                   (
                       SELECT value from answer_choices
                       WHERE a.answer = choice_id
                   )as rightAnswer,
                   (
                       SELECT
                       value
                       from answer_choices
                       WHERE
                       a.question_id = question_id
                       AND post = 'a' OR 'A'
                   )as choice1,
                    (
                       SELECT value
                       from answer_choices
                       WHERE
                       a.question_id = question_id
                       AND post = 'b' OR 'B'
                   )as choice2,
                    (
                       SELECT value
                       from answer_choices
                       WHERE
                       a.question_id = question_id
                       AND post = 'c' OR 'C'
                   )as choice3,
                    (
                       SELECT value
                       from answer_choices
                       WHERE
                       a.question_id = question_id
                       AND post = 'd' OR 'D'
                   )as choice4
                   FROM
                   questions a,
                   quizzes b,
                   admins c
                   WHERE
                   c.admin_id = b.admin_id
                   AND
                  b.quiz_id = $this->quizID";

           //Prepare Statement
           $stmt = $this->conn->prepare($query);

           //Execute Query
           $stmt->execute();

           return $stmt;
       }
         public function addQuiz() {
            $insertQuery = "INSERT INTO quizzes
                            SET
                              quiz_title = :quizTitle,
                              admin_id = :admin_id,
                              description = :description,
                              part_type = :part_type,
                              filepath = :filepath,
                              passingrate = :passingrate";

            $stmt = $this->conn->prepare($insertQuery);

            $this->quizTitle = htmlspecialchars(strip_tags($this->quizTitle));
            $this->part_type = htmlspecialchars(strip_tags($this->part_type));
            $this->description = htmlspecialchars(strip_tags($this->description));
            $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));
            $this->filepath = htmlspecialchars(strip_tags($this->filepath));
            $this->passingrate = htmlspecialchars(strip_tags($this->passingrate));

            // Bind parameters

            if($this->filepath == ''){
                $this->filepath = "../../../AdmInterfaceV2/uploads/quiz/default.jpeg";
            }
            $stmt->bindParam(':quizTitle', $this->quizTitle);
            $stmt->bindParam(':part_type', $this->part_type);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':admin_id', $this->admin_id);
            $stmt->bindParam(':filepath', $this->filepath);
            $stmt->bindParam(':passingrate', $this->passingrate);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

       public function addQuizInSharing() {
            $insertQuery = "INSERT INTO quizzes
                            SET
                              quiz_title = :quizTitle,
                              admin_id = :admin_id,
                              description = :description,
                              part_type = :part_type,
                              filepath = :filepath,
                              quizOwner = :quizOwner,
                              capability = :capability,
                              passingrate = :passingrate";

            $stmt = $this->conn->prepare($insertQuery);

            $this->quizTitle = htmlspecialchars(strip_tags($this->quizTitle));
            $this->part_type = htmlspecialchars(strip_tags($this->part_type));
            $this->description = htmlspecialchars(strip_tags($this->description));
            $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));
            $this->filepath = htmlspecialchars(strip_tags($this->filepath));
            $this->quizOwner = htmlspecialchars(strip_tags($this->quizOwner));
             $this->capability = htmlspecialchars(strip_tags($this->capability));
             $this->passingrate = htmlspecialchars(strip_tags($this->passingrate));
           
            // Bind parameters

            if($this->filepath == ''){
                $this->filepath = "../../../AdmInterfaceV2/uploads/quiz/default.jpeg";
            }
            $stmt->bindParam(':quizTitle', $this->quizTitle);
            $stmt->bindParam(':part_type', $this->part_type);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':admin_id', $this->admin_id);
            $stmt->bindParam(':filepath', $this->filepath);
            $stmt->bindParam(':quizOwner', $this->quizOwner);
            $stmt->bindParam(':capability', $this->capability);
             $stmt->bindParam(':passingrate', $this->passingrate);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
    
  public function readTrash(){
       //Create query
            $query = "SELECT 
            quiz_id,
            quiz_title,
            admin_id,
            description,
            filepath,
            (SELECT DATE_FORMAT(date_created, '%M %d, %Y')) as date_created,
            part_type,
            quizOwner,
            capability,
            passingrate
            FROM quizzes WHERE admin_id = $this->admin_id AND delete_date IS NOT NULL
                      ORDER BY
                      quiz_id ASC";

            //Prepare Statement
            $stmt = $this->conn->prepare($query);

            //Execute Query
            $stmt->execute();

            return $stmt;
  }
  //Read Quiz
    public function readQUiz() {
            //Create query
            $query = "SELECT
            a.quiz_id,
            a.quiz_title,
            (SELECT DATE_FORMAT(a.date_created, '%M %d, %Y')) as date_created,
            a.filepath,
            a.description,
            a.part_type,
            a.quizOwner,
            a.capability,
            ( SELECT Count(quiz_id) FROM quizzes
            Where admin_id = a.admin_id
                       ) as totalQuiz,
            (SELECT MAX(quiz_id) FROM quizzes
             Where admin_id = a.admin_id) as MaxID
            FROM
            quizzes a left join admins b
            on a.quiz_id = b.admin_id
            WHERE a.admin_id = $this->admin_id AND a.quiz_id NOT IN (SELECT quiz_id FROM quiz_tag_colllections) AND delete_date IS NULL
                ORDER BY
                      a.date_created DESC";

            //Prepare Statement
            $stmt = $this->conn->prepare($query);

            //Execute Query
            $stmt->execute();

            return $stmt;
        }

    public function readFreeFlow(){
      $query = "SELECT * FROM `quiz_parts` WHERE `quiz_id` = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(1, $this->quizID);
      $stmt->execute();
      return $stmt;
    }

 //Get Single Employee
 public function singleSY() {
    //Create query
    $query = "SELECT schoolyear_id,
    LEFT (schoolYear,4) AS start ,
    
    (SELECT 
    RIGHT (schoolYear,4)  
    FROM school_years
    WHERE schoolyear_id = $this->schoolyear_id) as end
    
    FROM school_years
    WHERE schoolyear_id = $this->schoolyear_id";
        
    //Prepate Statement
    $stmt = $this->conn->prepare($query);

    //Bind Student_ID
    $stmt->bindParam(1, $this->quizID);

    //Execute Query
    $stmt->execute();

    return $stmt;
}
        
 //Get Request Message
 public function getMsg() {
    //Create query
   $query = "SELECT message
   FROM admin_requests
   WHERE req_id = $this->req_id";
     
    //Prepate Statement
    $stmt = $this->conn->prepare($query);

    //Bind Student_ID
    $stmt->bindParam(1, $this->quizID);

    //Execute Query
    $stmt->execute();

    return $stmt;
}
        
    //Get Single Employee
 public function singleEmployee() {
    //Create query
   $query = "select a.admin_id, a.mirror_id,
   sec.section_id, s.section,
   CONCAT (b.fname,' ',b.mname,' ',b.lname) AS fullname
   from my_admins b inner join admins a
   on b.employee_id = a.mirror_id
   inner join sections_handled sec
   on a.admin_id = sec.admin_id
   inner join sections s 
   on sec.section_id = s.section_id 
   where sec.schoolyear_id in (select schoolyear_id from school_years where status = 1)";
        
    //Prepate Statement
    $stmt = $this->conn->prepare($query);

    //Bind Student_ID
    $stmt->bindParam(1, $this->quizID);

    //Execute Query
    $stmt->execute();

    return $stmt;
}
    //Get Single Quiz
 public function singleQuiz() {
    //Create query
    $query = "SELECT
              a.quiz_id,
              a.quiz_title,
              a.admin_id,
              a.description,
              a.filepath,
              a.part_type,
              a.passingrate,
              (SELECT DATE_FORMAT(a.date_created, '%M %d, %Y')) as date_created,
              (SELECT username from admins where admin_id = a.admin_id) as quizOwner,
              ( SELECT Max(quiz_id) FROM quizzes) as MaxID,
              ( SELECT count(part_id) FROM quiz_parts
              WHERE quiz_id = a.quiz_id) as partsTotal,
              ( SELECT Max(part_id) FROM quiz_parts) as MaxPart
              FROM 
              quizzes a
              WHERE
              `quiz_id` =  ? ";

    //Prepate Statement
    $stmt = $this->conn->prepare($query);

    //Bind Student_ID
    $stmt->bindParam(1, $this->quizID);

    //Execute Query
    $stmt->execute();

    return $stmt;
}

public function sharedQuizzes() {
        //Create query
        $query = "SELECT 
        quiz_title,
        (SELECT DATE_FORMAT(date_created, '%M %d, %Y %r (%a)')) as date_created,
        quizOwner,
        part_type,
        capability
        FROM quizzes WHERE admin_id = $this->admin_id
        and trim(coalesce(quizOwner, '')) <>'' ";

        //Prepare Statement
        $stmt = $this->conn->prepare($query);

        //Execute Query
        $stmt->execute();

        return $stmt;
    }
public function listofProf() {
        //Create query
        $query = "SELECT admins.admin_id,
        CONCAT (my_admins.lname, ', ' , my_admins.fname) AS username
        FROM my_admins INNER JOIN admins ON admins .mirror_id = my_admins.employee_id
        AND admins.admin_id != $this->admin_id";
//AND admins.admin_id != $this->admin_id
        //Prepare Statement
        $stmt = $this->conn->prepare($query);

        //Execute Query
        $stmt->execute();

        return $stmt;
    }

         public function getLastQuizId(){
            $query = "SELECT MAX(`quiz_id`) FROM quizzes";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->lastQId = $row['MAX(`quiz_id`)'];
         }

         public function setType() {
            $insertQuery = "INSERT INTO quiz_parts SET
                                type_id = :type_id,
                                quiz_id = :quiz_id,
                                duration = :duration,
                                position = :position";

            $stmt = $this->conn->prepare($insertQuery);

            $this->getLastQuizId();
            $this->getTypeID();

            $stmt->bindParam(':type_id', $this->type_id);
            $stmt->bindParam(':quiz_id', $this->lastQId);
            $this->totalParts = 1;
            $stmt->bindParam(':position', $this->totalParts);
            $stmt->bindParam(':duration', $this->duration);

            // Execute
            if ($stmt->execute()){
                return true;
            } else {
                return false;
            }
        }

         //Update
       public function updateSY() {
            $insertQuery = 'UPDATE school_years
                            SET
                              schoolYear = :schoolYear
                              WHERE
                              schoolyear_id = :schID';

           // Prepare Insert Statement
           $stmt = $this->conn->prepare($insertQuery);

            // Clean inputted data
           $this->schoolYear = htmlspecialchars(strip_tags($this->schoolYear));
           $this->schID = htmlspecialchars(strip_tags($this->schID));

            // Bind parameters
            $stmt->bindParam(':schoolYear', $this->schoolYear);
            $stmt->bindParam(':schID', $this->schID);

            // Execute
            if ($stmt->execute()) {
                return true;
            } else {
                printf("Error %s". \n, $stmt->err);
                return false;
            }
        }
        
        //Update
       public function updateQuiz() {
            $insertQuery = 'UPDATE quizzes
                            SET
                              quiz_title = :quizTitle,
                              description = :description,
                              filepath = :filepath
                              WHERE
                              quiz_id = :quizID';

           // Prepare Insert Statement
           $stmt = $this->conn->prepare($insertQuery);

            // Clean inputted data
           $this->quizTitle = htmlspecialchars(strip_tags($this->quizTitle));
           $this->description = htmlspecialchars(strip_tags($this->description));
           $this->quizID = htmlspecialchars(strip_tags($this->quizID));
           $this->filepath = htmlspecialchars(strip_tags($this->filepath));
           
           if($this->filepath == ''){
          $this->filepath = "../../../AdmInterfaceV2/uploads/quiz/default.jpeg";
        }
            // Bind parameters
            $stmt->bindParam(':quizTitle', $this->quizTitle);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':quizID', $this->quizID);
            $stmt->bindParam(':filepath', $this->filepath);
           
            // Execute
            if ($stmt->execute()) {
                return true;
            } else {
                printf("Error %s". \n, $stmt->err);
                return false;
            }
        }

        //UPDATE QUESTION
       public function updateTrueorFalse(){

           $updateQuery = " UPDATE questions
                            SET question= :new_question,
                                quiz_id = :quizID,
                                part_id = :partID,
                                filepath = :filepath
                            WHERE question_id = :question_id";

           $stmt = $this->conn->prepare($updateQuery);
           
           if($this->filepath == ''){
          $this->filepath = "../../../AdmInterfaceV2/uploads/quiz/default.jpeg";
           }
           $stmt->bindParam(':new_question', $this->new_question);
           $stmt->bindParam(':quizID',  $this->quizID );
           $stmt->bindParam(':partID', $this->partID);
           $stmt->bindParam(':question_id', $this->question_id);
           $stmt->bindParam(':filepath', $this->filepath);

           //TESTING
           if($stmt->execute()){
               if($this->updateChoices()){
                   return true;
               }
           }else {
               return false;
           }
       }
         //UPDATE QUESTION
       public function updateChoices() {
       $updateQuery = "UPDATE answer_choices
                       SET
                           quiz_id = :quizID,
                           value = :value
                           WHERE choice_id =(SELECT answer FROM questions WHERE question_id = :question_id) ";

       $stmt = $this->conn->prepare($updateQuery);

           $stmt->bindParam(':quizID',  $this->quizID );
           $stmt->bindParam(':value', $this->correct);
           $stmt->bindParam(':question_id', $this->question_id);

           if ($stmt->execute()){
           return true;
       }else{
           return false;
       }
   }
          public function addQuestion() {
        $insertQuery = "INSERT INTO questions
                        SET
                            quiz_id = :quiz_id,
                            part_id = :part_id,
                            question = :question,
                            filepath = :filepath";

        $stmt = $this->conn->prepare($insertQuery);
        $this->quizID = htmlspecialchars(strip_tags($this->quizID));
        $this->totalParts = htmlspecialchars(strip_tags($this->part_id));
        $this->duration = htmlspecialchars(strip_tags($this->question));

        if($this->filepath == ''){
          $this->filepath = "../../../AdmInterfaceV2/uploads/quiz/default.jpeg";
        }
        $stmt->bindParam(':filepath', $this->filepath);
        $stmt->bindParam(':quiz_id', $this->quizID);
        $stmt->bindParam(':part_id', $this->part_id);
        $stmt->bindParam(':question', $this->question);

        if($stmt->execute()){
            if($this->insertChoices()){
                return true;
            }
        }else{
            return false;
        }

    }
          public function GenericAddQuestion() {
        $insertQuery = "INSERT INTO questions
                        SET
                            quiz_id = :quiz_id,
                            part_id = :part_id,
                            question = :question,
                            filepath = :filepath";

        $stmt = $this->conn->prepare($insertQuery);
        $this->quizID = htmlspecialchars(strip_tags($this->quizID));
        $this->totalParts = htmlspecialchars(strip_tags($this->part_id));
        $this->duration = htmlspecialchars(strip_tags($this->question));

        if($this->filepath == ''){
          $this->filepath = "../../../AdmInterfaceV2/uploads/quiz/default.jpeg";
        }
        $stmt->bindParam(':filepath' , $this->filepath);
        $stmt->bindParam(':quiz_id', $this->quizID);
        $stmt->bindParam(':part_id', $this->part_id);
        $stmt->bindParam(':question', $this->question);

        if($stmt->execute()){
            if($this->GenericinsertToAnswerChoices()){
                return true;
            }
        }else{
            return false;
        }

    }

    public function insertChoices() {
        $counter = 0;
        $insertQuery = "INSERT INTO answer_choices
                        SET
                            question_id = (select max(question_id) from questions),
                            quiz_id = :quiz_id,
                            value = :value,
                            post = :order";

        $stmt = $this->conn->prepare($insertQuery);

        foreach ($this->values as $val){

            $this->question_id = htmlspecialchars(strip_tags($this->question_id));
            $this->quizID = htmlspecialchars(strip_tags($this->quizID));
            $this->order = htmlspecialchars(strip_tags($this->order));
            $val = htmlspecialchars(strip_tags($val));
            $stmt->bindParam(':quiz_id', $this->quizID);
            $stmt->bindParam(':value', $val);
            $stmt->bindParam(':order', $this->order);

            if ($stmt->execute()){
                $counter++;
                $this->order++;
            }
        }

        if($counter==4){
            return true;
        }else{
            return false;
        }


    }
        public function GenericinsertToAnswerChoices() {
        $insertQuery = "INSERT INTO answer_choices
                        SET
                            question_id = (select max(question_id) from questions),
                            quiz_id = :quiz_id,
                            value = :value";

        $stmt = $this->conn->prepare($insertQuery);

            $this->question_id = htmlspecialchars(strip_tags($this->question_id));
            $this->quizID = htmlspecialchars(strip_tags($this->quizID));
            $this->value = htmlspecialchars(strip_tags($this->correct));

            $stmt->bindParam(':quiz_id', $this->quizID);
            $stmt->bindParam(':value', $this->correct);

            if ($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
        public function searchQuiz() {
            //Select query
            $query =  "SELECT
                        a.quiz_id,
                        a.quiz_title,
                       (
                       SELECT Count(quiz_id) FROM quiz_parts
                       Where quiz_id = a.quiz_id
                       ) as partsperQuiz
                       FROM
                       quizzes a
                            WHERE
                                a.quiz_title LIKE '%".$_GET['quiz_title']."%'";

             //Prepare Statement
            $stmt = $this->conn->prepare($query);

            //Execute Query
            $stmt->execute();

            return $stmt;
        }

        public function getQuizID() {
            $query = "SELECT quiz_id FROM quizzes WHERE quiz_title = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->quizTitle);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->quiz_id = $row['quiz_id'];
        }

        public function getTypeID() {
            $query = "SELECT type_id FROM question_types WHERE type = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->type_name);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->type_id = $row['type_id'];
        }

        public function countParts() {
            $query = "SELECT MAX(q.position) FROM quiz_parts q WHERE q.quiz_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->quizID);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->totalParts = $row['MAX(q.position)'];
            return $this->totalParts;
        }

        public function addQuizPart() {
            $insertQuery = "INSERT INTO quiz_parts SET
                                type_id = :type_id,
                                quiz_id = :quiz_id,
                                part_title = :part_title,
                                duration = :duration,
                                position = :position";

            $stmt = $this->conn->prepare($insertQuery);

            // Bind parameters
            $stmt->bindParam(':type_id', $this->type_id);
            $stmt->bindParam(':quiz_id', $this->quizID);
            $stmt->bindParam(':part_title', $this->part_title);
            $stmt->bindParam(':position', $this->totalParts);
            $stmt->bindParam(':duration', $this->duration);

            // Execute
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function checkPartTitle($partTitle, $quizID) {
            $query = "SELECT * FROM quiz_parts WHERE part_title = :part_title AND quiz_id = :quiz_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':part_title', $partTitle);
            $stmt->bindParam(':quiz_id', $quizID);

            $stmt->execute();
            $result = $stmt->rowCount();
            return $result;
        }

        public function updateQuizPart(){

            $updateQuery = " UPDATE quiz_parts
                             SET part_title= :new_part_title,
                                 type_id= :new_type_id ,
                                 duration = :duration
                             WHERE part_id = :part_id";

            $stmt = $this->conn->prepare($updateQuery);

            $stmt->bindParam(':new_part_title', $this->new_part_title);
            $stmt->bindParam(':new_type_id',  $this->type_id );
            $stmt->bindParam(':part_id', $this->part_id);
            $stmt->bindParam(':duration', $this->duration);

            //TESTING
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function viewQuizList() {
            $query = 'SELECT * FROM quiz ORDER BY quizTitle ' . $_GET['order'];

            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            // Execute Query
            $stmt->execute();

            return $stmt;
        }


        public function searchQuizPart() {
            //Select query
            $query =
            "SELECT
            a.part_title,
            a.duration,
            b.type
           FROM
            quiz_parts a left join question_types b
            on a.type_id = b.type_id
                WHERE
                  a.part_title LIKE '%".$_GET['part_title']."%'";

             //Prepare Statement
            $stmt = $this->conn->prepare($query);

            //Execute Query
            $stmt->execute();

            return $stmt;
        }

        public function blankGuessWord($word){

            $numOfLoops = floor((strlen($word) * .5));
            $array= array();
            while ($numOfLoops > 0) {
                $found = 0;
                $randomLoc = rand(0,strlen($word)-1);
                array_push($array, $randomLoc);
                for ($q=0; $q < count($array); $q++) {
                    if($randomLoc == $array[$q]){
                        $found += 1;
                    }
                }
                if($found == 1 && $word[$randomLoc] != " "){
                    $word[$randomLoc] = "_";
                }else{
                    $numOfLoops++;
                }
                $numOfLoops--;
            }
            echo $word;
        }


        public function insertAnswer(){

            $query = "SELECT max(choice_id) from answer_choices WHERE value = '$this->correct'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $this->answer_id = $row['max(choice_id)'];
            }

            $query = "SELECT max(question_id) FROM questions";
             $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $this->question_id = $row['max(question_id)'];
            }

            $updateQuery = "UPDATE questions set answer = '$this->answer_id' WHERE question_id = $this->question_id";
            $stmt = $this->conn->prepare($updateQuery);

            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
    }

        public function GenericInsertQuestion(){
            $query = "SELECT max(choice_id) from answer_choices WHERE value = '$this->correct'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $this->answer_id = $row['max(choice_id)'];
            }
            $query = "SELECT max(question_id) FROM questions";
             $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $this->question_id = $row['max(question_id)'];
            }
            $updateQuery = "UPDATE questions set answer = '$this->answer_id' WHERE question_id = $this->question_id";
            $stmt = $this->conn->prepare($updateQuery);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }

    }

    public function viewQuizParts() {
        $query = "SELECT q.quiz_id, q.quiz_title,q.filepath, q.description,q.capability, qt.type_id, pr.part_id, pr.part_title,
        (SELECT count(question) FROM questions WHERE part_id = pr.part_id) as 'totalQs' ,(SELECT count(part_title) FROM quiz_parts WHERE quiz_id = q.quiz_id) as 'totalParts',pr.duration,pr.position,
         qt.type FROM quiz_parts pr
        INNER JOIN quizzes q ON q.quiz_id = pr.quiz_id
        INNER JOIN question_types qt ON qt.type_id = pr.type_id
        WHERE q.quiz_id = $this->quizID ORDER BY pr.position ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function singlePart(){
        $query = "SELECT pr.part_title, pr.duration, ty.type
        FROM quiz_parts pr INNER JOIN question_types ty ON pr.type_id = ty.type_id
        WHERE pr.part_id = $this->part_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }


    public function selectAllFromQuiz() {
        $query = "SELECT * FROM quizzes WHERE quiz_id = $this->quizID";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function updateQuestion(){
        $query = "UPDATE questions SET question = :question, filepath = :filepath
                 WHERE question_id = :question_id";
        $stmt = $this->conn->prepare($query);
         if($this->filepath == ''){
          $this->filepath = "../../../AdmInterfaceV2/uploads/quiz/default.jpeg";
           }
        $this->question =  htmlspecialchars(strip_tags($this->question));
        $this->question_id =  htmlspecialchars(strip_tags($this->question_id));
        $stmt->bindParam(':question', $this->question);
        $stmt->bindParam(':filepath', $this->filepath);
        $stmt->bindParam(':question_id', intval($this->question_id));

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    //get all choices by question_id
    public function fetchChoices(){
        $query = "SELECT * FROM answer_choices WHERE question_id = :question_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':question_id', $this->question_id);
        $stmt->execute();
        $result = $stmt;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
              array_push($this->choices_keys, $row['choice_id']);
        }
    }

   public function updateAnswerChoices($fuckingKey, $fuckingValue){
       $query = "UPDATE answer_choices SET value = :value WHERE choice_id = :choice_id";
       $stmt = $this->conn->prepare($query);
       $stmt->bindParam(':value', $fuckingValue);
       $stmt->bindParam(':choice_id', $fuckingKey);
       $stmt->execute();
       return $stmt;
   }

   //select all with 2 where conditions
   //table name, column, columncompare, column2, column2compare
   public function selectAll($tblname, $col, $colCompare, $col2, $col2Compare) {
       $query = "SELECT * FROM $tblname WHERE $col = :$col and $col2 = :$col2";
       $stmt = $this->conn->prepare($query);
       $stmt->bindParam(":$col", $colCompare);
       $stmt->bindParam(":$col2", $col2Compare);
       $stmt->execute();
       return $stmt;
   }
        
    public function selectAllArrange($tblname, $col2, $col2Compare) {
       $query = "SELECT * FROM $tblname WHERE $col2 = :$col2";
       $stmt = $this->conn->prepare($query);
       $stmt->bindParam(":$col2", $col2Compare);
       $stmt->execute();
       return $stmt;
   }

    public function updateSomething($tblname, $col, $colCompare, $condition, $conditionValue){
       $updateQuery = "UPDATE $tblname SET $col = :$col, filepath = :filepath WHERE $condition = :$condition";
       $stmt = $this->conn->prepare($updateQuery);
        if($this->filepath == ''){
                $this->filepath = "../../../AdmInterfaceV2/uploads/quiz/default.jpeg";
            }
       $stmt->bindParam(":$col", $colCompare);
       $stmt->bindParam(":filepath", $this->filepath);
       $stmt->bindParam(":$condition", $conditionValue);
       if($stmt->execute()){
           return true;
       }else{
           return false;
       }
   }
        
         public function updateSomethingArrange($tblname, $col, $colCompare, $condition, $conditionValue){
       $updateQuery = "UPDATE $tblname SET $col = :$col WHERE $condition = :$condition";
       $stmt = $this->conn->prepare($updateQuery);
       $stmt->bindParam(":$col", $colCompare);
       $stmt->bindParam(":$condition", $conditionValue);
       if($stmt->execute()){
           return true;
       }else{
           return false;
       }
   }
        
   public function selectQuizType($adminid , $quizid){
        $query = 'SELECT q.quiz_id, p.type_id, t.type
        FROM quizzes q
        INNER JOIN quiz_parts p
        ON q.quiz_id = p.quiz_id
        INNER JOIN question_types t on t.type_id = p.type_id
        WHERE q.admin_id = ?';
    }

    public function getTypePartId(){
        $query = "SELECT * FROM quiz_parts WHERE quiz_id = :quizId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quizId', $this->quizID);
        $stmt->execute();
        return $stmt;
    }

    public function filterQuizByTag($admin_id, $tag_id){
        $query = "SELECT q.quiz_id, qtc.tag_id, tag.tag_name, q.quiz_title, q.description, q.filepath, (SELECT DATE_FORMAT(q.date_created, '%M %d, %Y')) as date_created, q.part_type, q.quizOwner,q.capability FROM quizzes
                  q INNER JOIN quiz_tag_colllections qtc ON q.quiz_id = qtc.quiz_id INNER JOIN quiz_tags tag ON qtc.tag_id = tag.tag_id
                  WHERE q.admin_id = $admin_id AND tag.tag_id = $tag_id  ORDER BY
                      q.date_created DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quizId', $this->quizID);
        $stmt->execute();
        return $stmt;
    }


    public function checkStream($adminId){
        $query = "SELECT a.admin_id FROM admins a INNER JOIN up_quiz u ON a.admin_id = u.admin_id WHERE :admin_id IN (SELECT admin_id FROM up_quiz)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':admin_id', $adminId);
        $stmt->execute();
        $rowCount = $stmt->rowCount();

        if($rowCount > 0){
            return true;
        }else{
            return false;
        }
    }

    public function StreamQuiz($admin_id , $section_id , $quiz_id){
        $query = "INSERT INTO `up_quiz`(`admin_id`, `section_id`, `quiz_id`) VALUES ($admin_id,$section_id,$quiz_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':admin_id', $adminId);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
        
    public function removeTag($tagID, $quizID, $operation){
        if($operation==1){
            $query = "DELETE FROM quiz_tag_colllections WHERE tag_id = :tag_id and quiz_id = :quiz_id";
              $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tag_id', $tagID);
        $stmt->bindParam(':quiz_id', $quizID);
        }else{
            $query = "DELETE FROM quiz_tag_colllections WHERE quiz_id = :quiz_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':quiz_id', $quizID);
        }
      
      
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    
      //get quiz Taken by Section
    public function getTakenQuizSection($section_id){
      $query = "SELECT qt.quiz_id, 
      q.quiz_title, q.description, 
      sec.section, (SELECT DATE_FORMAT(qt.time_started, '%M %d, %Y')) as time_started, 
      qt.room_id FROM quiz_takers qt 
      INNER JOIN quizzes q ON qt.quiz_id = q.quiz_id 
      INNER JOIN sections sec on qt.section_id = sec.section_id 
      WHERE qt.semester_id IN (SELECT schoolyear_id FROM school_years WHERE STATUS = 1) 
      and qt.section_id = :section_id";

      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':section_id', $section_id);
      $stmt->execute();
      return $stmt;
    }

    public function getQuizSummaryHeader($quiz_id , $section_id){
      $query = "SELECT qtk.quiz_id, q.quiz_title, adm.mirror_id, ma.fname, ma.mname, ma.lname, (SELECT DATE_FORMAT(qtk.time_started, '%M %d, %Y / %r')) as time_started, q.part_type, sech.section_id, sec.section, c.course, c.course_prefix FROM quiz_takers qtk INNER JOIN quizzes q on qtk.quiz_id = q.quiz_id
      INNER JOIN admins adm on qtk.admin_id = adm.admin_id
      INNER JOIN my_admins ma on adm.mirror_id = ma.employee_id
      INNER JOIN sections_handled sech on qtk.section_id = sech.section_id
      INNER JOIN sections sec on sech.section_id = sec.section_id
      INNER JOIN courses c on sec.course_id = c.course_id WHERE sech.schoolyear_id IN (select schoolyear_id FROM school_years WHERE STATUS = 1) AND qtk.quiz_id = :quiz_id AND qtk.section_id = :section_id";

      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':quiz_id', $quiz_id);
      $stmt->bindParam(':section_id', $section_id);
      $stmt->execute();
      return $stmt;
    }

    public function getStudentsAnswer($quiz_id , $room_id , $user_id){
      $query="SELECT DISTINCT(qst.question), uan.student_section_id, studsec.schoolyear_id, sec.section, uan.answer, uan.status, ac.value FROM questions qst INNER JOIN user_answers uan on qst.question_id = uan.question_id INNER JOIN user_accounts uac ON uan.user_id = uac.user_id INNER JOIN students_sections studsec on uan.student_section_id = studsec.section_id INNER JOIN sections sec on studsec.section_id = sec.section_id INNER JOIN answer_choices ac on qst.answer = ac.choice_id WHERE qst.quiz_id = :quiz_id AND studsec.schoolyear_id IN (select (schoolyear_id) FROM school_years WHERE STATUS = 1) and uan.room_id = :room_id and uan.user_id = :user_id";

      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':quiz_id', $quiz_id);
      $stmt->bindParam(':room_id', $room_id);
      $stmt->bindParam(':user_id', $user_id);
      $stmt->execute();
      return $stmt;
    }

    public function getPassersOnly($room_id){
      $query ="SELECT DISTINCT(uan.user_id), uac.student_id, st.fname, st.mname, st.lname, 
        (SELECT count(status) FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id) as overall,
         (SELECT count(status) FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id and status = 1) as score, 
        (SELECT count(status) FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id and status = 1)/(SELECT count(status) 
        FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id)*50+50 as equivalent FROM user_answers uan INNER JOIN user_accounts uac ON 
        uan.user_id = uac.user_id 
        INNER JOIN students st ON uac.student_id = st.student_id WHERE uan.room_id = :room_id AND
         (SELECT count(status) FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id and status = 1)/(SELECT count(status) FROM 
        user_answers where user_id = uan.user_id AND room_id = uan.room_id)*50+50 >= 75";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':room_id', $room_id);
        $stmt->execute();
        return $stmt;
    }

    public function getPassersFailures($room_id){
      $query = "SELECT DISTINCT(uan.user_id), uac.student_id, st.fname, st.mname, st.lname, 
        (SELECT count(status) FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id) as overall,
        (SELECT count(status) FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id and status = 1) as score,
        (SELECT count(status) FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id and status = 1)/(SELECT count(status) 
        FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id)*50+50 as equivalent,  IF ((SELECT count(status) FROM user_answers 
        where user_id = uan.user_id AND room_id = uan.room_id and status = 1)/(SELECT count(status) FROM user_answers where user_id = uan.user_id 
        AND room_id = uan.room_id)*50+50 >= 75, 'PASSED', 'FAILED') as remarks FROM user_answers uan INNER JOIN user_accounts uac ON uan.user_id = uac.user_id 
        INNER JOIN students st ON uac.student_id = st.student_id WHERE uan.room_id = :room_id ORDER BY equivalent DESC";

      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':room_id', $room_id);
      $stmt->execute();
      return $stmt;
    }

    public function getOfficialStudnets($section_id){
      $query = "SELECT st.student_id, st.fname, st.mname, st.lname, stdc.section_id, sec.section, c.course_prefix ,c.course FROM students st INNER JOIN students_sections stdc ON st.student_id = stdc.student_id inner join sections sec on stdc.section_id = sec.section_id 
      inner join courses c on sec.course_id = c.course_id WHERE stdc.schoolyear_id IN (select schoolyear_id FROM school_years where status =1) AND
      stdc.section_id = :section_id";

      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':section_id', $section_id);
      $stmt->execute();
      return $stmt; 
    }

    public function getFailuresOnly($room_id){
      $query = "SELECT DISTINCT(uan.user_id), uac.student_id, st.fname, st.mname, st.lname, (SELECT count(status) FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id) as overall, (SELECT count(status) FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id and status = 1) as score, (SELECT count(status) FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id and status = 1)/(SELECT count(status) FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id)*50+50 as equivalent FROM user_answers uan INNER JOIN user_accounts uac ON uan.user_id = uac.user_id 
       INNER JOIN students st ON uac.student_id = st.student_id WHERE uan.room_id = :room_id AND (SELECT count(status) FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id and status = 1)/(SELECT count(status) FROM user_answers where user_id = uan.user_id AND room_id = uan.room_id)*50+50 < 75";
      
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':room_id', $room_id);
      $stmt->execute();
      return $stmt;
    }

    public function getPassersPerQuestion($quiz_id , $room_id){
      $query = "SELECT DISTINCT(qst.question), uan.student_section_id, studsec.schoolyear_id, sec.section, (select count(status) overall FROM user_answers where room_id = uan.room_id and STATUS =1 and question_id = uan.question_id) 'nakatama', (select count(status) overall FROM user_answers where room_id = uan.room_id and STATUS =0 and question_id = uan.question_id) 'nakamali' FROM questions qst  INNER JOIN user_answers uan on qst.quiz_id = uan.quiz_id INNER JOIN user_accounts uac ON uan.user_id = uac.user_id INNER JOIN students_sections studsec on uan.student_section_id = studsec.section_id INNER JOIN sections sec on studsec.section_id = sec.section_id WHERE qst.quiz_id = :quiz_id AND studsec.schoolyear_id IN (select (schoolyear_id) FROM school_years WHERE STATUS = 1) and uan.room_id = :room_id";
      
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':quiz_id', $quiz_id);
      $stmt->bindParam(':room_id', $room_id);
      $stmt->execute();
      return $stmt;
    }

    public function answerBy($user_id , $room_id){
      $query = "SELECT DISTINCT(qtk.time_started), uan.user_id, uac.student_id, st.fname, st.mname, st.lname, stc.section_id, sec.section, c.course, c.course_prefix, qtk.time_started,q.quiz_title FROM quiz_takers qtk INNER JOIN user_answers uan ON qtk.room_id = uan.room_id 
        INNER JOIN user_accounts uac ON uan.user_id
        INNER JOIN students st ON uac.student_id = st.student_id
        INNER JOIN students_sections stc ON uan.student_section_id = stc.section_id
        INNER JOIN sections sec ON stc.section_id = sec.section_id
        INNER JOIN courses c ON sec.course_id = c.course_id
        INNER JOIN quizzes q ON qtk.quiz_id = q.quiz_id
        WHERE uac.user_id = :user_id and uan.user_id = :user_id and stc.schoolyear_id IN (SELECT schoolyear_id FROM school_years WHERE status = 1) and qtk.room_id = :room_id";

      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':user_id', $user_id);
      $stmt->bindParam(':room_id', $room_id);
      $stmt->execute();
      return $stmt;
    }
            public function sectionHeader($section_id){
      $query = "SELECT s.section, c.course, c.course_prefix FROM sections s INNER JOIN courses c ON s.course_id = c.course_id where s.section_id = :section_id";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':section_id', $section_id);
      $stmt->execute();
      return $stmt;
    }

    public function whoPrinted($admin_id){
      $query = "SELECT a.`admin_id` , ma.employee_id , a.mirror_id , ma.fname , ma.lname FROM admins a INNER JOIN my_admins ma on ma.employee_id = a.mirror_id WHERE a.admin_id = :admin_id";

      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':admin_id', $admin_id);
      $stmt->execute();

      if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        return $row['fname']." ".$row['lname'];
      }

    }

    public function deletePrevRecord($quizToken, $secID){
        $query = "DELETE uan FROM user_answers uan INNER JOIN quiz_takers qtk ON uan.quiz_token = qtk.quiz_token WHERE qtk.quiz_token = :quizToken and qtk.section_id = :secID AND qtk.semester_id IN (SELECT schoolyear_id FROM school_years WHERE status = 1)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quizToken', $quizToken);
         $stmt->bindParam(':secID', $secID);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function getQuizToken($roomID){
        $query = "SELECT quiz_token FROM quiz_takers WHERE room_id = $roomID and semester_id IN (SELECT schoolyear_id FROM school_years WHERE status = 1)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          return $row['quiz_token'];
        }
    }

    public function recordQuiz($section_id,$admin_id,$quiz_id,$room_id,$quiz_token){

        $query = "INSERT INTO `quiz_takers`(`section_id`,`room_id`, `admin_id`, `quiz_id`, `semester_id` ,`quiz_token`) VALUES 
        ( :section_id , :room_id, :admin_id, :quiz_id, (SELECT schoolyear_id FROM school_years WHERE STATUS = 1) , :quiz_token)";
        $stmt = $this->conn->prepare($query);
       
        $stmt->bindParam(':section_id', $section_id);
        $stmt->bindParam(':room_id', $room_id);
        $stmt->bindParam(':admin_id', $admin_id);
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->bindParam(':quiz_token', $quiz_token);
        
        if($stmt->execute()){
              return true;
        }else{
            return false;
        }

    }

    public function recordMyAnswer($answer , $question_id , $user_id , $status , $student_section_id , $speed , $quiz_id , $room_id , $quiz_token){
      $query = "INSERT INTO `user_answers`
      ( `answer`, `question_id`, `quiz_id` , `user_id`, `status`, `student_section_id`, `time`, `room_id` , `quiz_token`) VALUES 
      (:answer , :question_id , :quiz_id , :user_id , :status , :student_section_id , :speed , :room_id , :quiz_token)";
      $stmt = $this->conn->prepare($query);
     
      $stmt->bindParam(':answer', $answer);
      $stmt->bindParam(':question_id', $question_id);
      $stmt->bindParam(':user_id', $user_id);
      $stmt->bindParam(':status', $status);
      $stmt->bindParam(':student_section_id', $student_section_id);
      $stmt->bindParam(':speed', $speed);
      $stmt->bindParam(':quiz_id', $quiz_id);
      $stmt->bindParam(':room_id', $room_id);
      $stmt->bindParam(':quiz_token', $quiz_token);
      
      if($stmt->execute()){
            return true;
        }else{
            return false;
      }
    }

    public function submitFeedback($feedback_score , $section_id , $user_id , $quiz_id){

      $query = "INSERT INTO `feedbacks`(`feedback_score`, `section_id`, `user_id` , `quiz_id`) VALUES (:feedback_score , :section_id , :user_id , :quiz_id)";

      $stmt = $this->conn->prepare($query);
     
      $stmt->bindParam(':feedback_score', $feedback_score);
      $stmt->bindParam(':section_id', $section_id);
      $stmt->bindParam(':user_id', $user_id);
      $stmt->bindParam(':quiz_id', $quiz_id);
      
      if($stmt->execute()){
            return true;
        }else{
            return false;
      }

    }

}


