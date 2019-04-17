<?php 
require_once '../../vendor/autoload.php';
require_once '../../models/Hosts.php';
require_once '../../models/Quiz.php';
require_once '../../config/Database.php';

//SETTINGS FOR FONT
$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

//HEADER
$head = ('
	<div class="header">
		<div class="left">
			<img class="imgfit" src="..\..\vendor\assets\Bulacan_State_University_logo.png">
		</div>

		<div class="middle">
			<h4 class="q">Bulacan State University</h4>
			<p class="q">College of Information and Communications Technology</p>
			<p class="q">Quizzen Answer Key</p>
		</div>

		<div class="right">
			<img class="imgfit" src="..\..\vendor\assets\CICT.png">
		</div>
	</div>');



function produceNeck(){

	$neck = '';

	$database = new Database();
	$db = $database->connect();
	$quizModel = new Quiz($db);

	$quizModel->quizID = $_GET['id'];
	$result = $quizModel->singleQuiz();

	if($row = $result->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		$neck.="
		<div class='midDiv'>
			<table class='shameless'>
				<tr>
					<td><b>Quizzen Name:</b></td>
					<td>".$quiz_title."</td>
				</tr>
				<tr>
					<td><b>Description:</b></td>
					<td>".$description."</td>
				</tr>
				<tr>
					<td><b>Quizzen Created:</b></td>
					<td>".$date_created."</td>
				</tr>
				<tr>
					<td><b>Quizen By:</b></td>
					<td>Chris Joshua Manuel</td>
				</tr>
			</table>
		</div>
		";
	}

	return $neck;
}

function produceBody(){
	$body = '';
	$partCntr = 0;
	$database = new Database();
	$db = $database->connect();
	$quizModel = new Quiz($db);

	$quizModel->quizID = $_GET['id'];
	$result = $quizModel->getTypePartId();

	while($partInfo =  $result->fetch(PDO::FETCH_OBJ)){ // habang may nakukuha kang part doon sa quiz
		$partCntr++;
		$quizModel->partID = $partInfo->part_id;
		if($partInfo->type_id == 3){

			$qCounter = 0;

			$body .="<div class='partDiv'>
							<div class='partTitleArr'>
								<b>Part ".$partCntr.": ".$partInfo->part_title."</b> <b>/ Arrange the Sequence </b>
							</div>

								<table class='tableArr'>
								<tr>
									<th style='width:80%;'>? Question</th>
									<th>Sequence</th>
								</tr>
							";

			$resultRead = $quizModel->readQuestions();
			while($quizInfo = $resultRead->fetch(PDO::FETCH_OBJ)){
				$qCounter++;
				$body.="
							<tr>
								<td>".$quizInfo->question."</td>
								<td class='ArrAns'>
								<p>1: ".$quizInfo->choice1."</p>
								<p>2: ".$quizInfo->choice2."</p>
								<p>3: ".$quizInfo->choice3."</p>
								<p>4: ".$quizInfo->choice4."</p>
								</td>
							</tr>
						";
			}

			if($qCounter<1){
				$body .="
					<tr>
						<td>There were no questions here.</td>
					</tr>
				";
			}

			$body.="</table></div>"; //this closes the goddamn div

		}


		if($partInfo->type_id == 1){

			$qCounter = 0;

			$body .="<div class='partDiv'>
							<div class='partTitleMulti'>
								<b>Part ".$partCntr.": ".$partInfo->part_title."</b> <b>/ MultipleChoice </b>
							</div>

								<table class='tableMulti'>
								<tr>
									<th style='width:80%;'>? Question</th>
									<th> &#10004; Choices</th>
								</tr>
							";

			$resultRead = $quizModel->readQuestions();
			while($quizInfo = $resultRead->fetch(PDO::FETCH_OBJ)){
				$qCounter++;
				if($quizInfo->choice1 = $quizInfo->rightAnswer){$quizInfo->choice1 = $quizInfo->choice1 . "<b>&#10004</b>";}
				$body.="
							<tr>
								<td>".$quizInfo->question."</td>
								<td class='multiAns'>
								<p>A. ".$quizInfo->choice1."</p>
								<p>B. ".$quizInfo->choice2."</p>
								<p>C. ".$quizInfo->choice3."</p>
								<p>D. ".$quizInfo->choice4."</p>
								<p>D. ".$quizInfo->rightAnswer."</p>
								</td>
							</tr>
						";
			}

			if($qCounter<1){
				$body .="
					<tr>
						<td>There were no questions here.</td>
					</tr>
				";
			}

			$body.="</table></div>"; //this closes the goddamn div

		}

		if($partInfo->type_id == 2){
			
			$qCounter = 0;

			$body .="
				<div class='partDiv'>
					<div class='partTitleTrue'>
						<b>Part ".$partCntr.": ".$partInfo->part_title."</b> <b>/ TRUE or FALSE </b>
					</div>
					<table class='tableTrue'>
						<tr>
							<th style='width:80%;'>? Question</th>
							<th> &#10004; Answer</th>
						</tr>
						";

			$resultRead = $quizModel->readQuestions();
			while($quizInfo = $resultRead->fetch(PDO::FETCH_OBJ)){
				$qCounter++;
				$body.="
							<tr>
								<td>".$quizInfo->question."</td>
								<td class='trueAns'>".$quizInfo->rightAnswer."</td>
							</tr>
						";
			}

			if($qCounter<1){
				$body .="
					<tr>
						<td>There were no questions here.</td>
					</tr>
				";
			}

			$body.="</table></div>";
		}

		if($partInfo->type_id == 4){

			$qCounter = 0;

			$body .="
				<div class='partDiv'>
					<div class='partTitleGuess'>
						<b>Part ".$partCntr.": ".$partInfo->part_title."</b> <b>/ Guess the Word </b>
					</div>
					<table class='tableGuess'>
						<tr>
							<th style='width:80%;'>? Question</th>
							<th> &#10004; Answer</th>
						</tr>
						";

			$resultRead = $quizModel->readQuestions();
			while($quizInfo = $resultRead->fetch(PDO::FETCH_OBJ)){
				$qCounter++;
				$body.="
							<tr>
								<td>".$quizInfo->question."</td>
								<td class='guessAns'>".$quizInfo->rightAnswer."</td>
							</tr>
						";
			}

			if($qCounter<1){
				$body .="
					<tr>
						<td>There were no questions here.</td>
					</tr>
				";
			}			
			$body.="</table></div>";	
		}
	}

	return $body;

}

	$mpdf = new \Mpdf\Mpdf([
	'fontDir' => array_merge($fontDirs, [__DIR__]),
	'fontdata' => $fontData + ['SamsungSans-Regular' => [
		'R' => '..\..\vendor\assests\SamsungSans-Regular.ttf',
	]],
	'default_font' => 'SamsungSans-Regular'
]);

$stylesheet = file_get_contents('..\..\vendor\assets\thecss.css');
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($head);

$theNeck = produceNeck();
$mpdf->WriteHTML($theNeck);

$theBodeh = produceBody();
$mpdf->WriteHTML($theBodeh);

$mpdf->setFooter('Quizzen {PAGENO} / {nb}');
$mpdf->Output();

?>