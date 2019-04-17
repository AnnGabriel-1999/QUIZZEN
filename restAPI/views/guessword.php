<?php 
include_once '../models/Quiz.php';
include_once '../config/Database.php';

$database = new Database();
$db = $database->connect();
//Instantiate Users Class
$quiz = new Quiz($db);

$quiz->blankGuessWord("PROGRAMMING");
?>