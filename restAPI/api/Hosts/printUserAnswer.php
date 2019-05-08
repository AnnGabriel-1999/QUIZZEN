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

$head = ('
	<div class="header">
		<div class="left">
			<img class="imgfit" src="..\..\vendor\assets\Bulacan_State_University_logo.png">
		</div>

		<div class="middle">
			<h4 class="q">Bulacan State University</h4>
			<p class="q">College of Information and Communications Technology</p>
			<p class="q">Malolos City of Bulacan</p>
			<p class="q"><b style="font-size:13px;">User Answer List</b></p>
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

	$result = $quizModel->answerBy($_GET['user_id'],$_GET['room_id']);

	if($row = $result->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		$neck.="
		<div class='midDiv' style='margin-top:10px;'>
			<table class='shameless'>
				<tr>
					<td><b>Quizzen Title:</b></td>
					<td>".$quiz_title."</td>
				</tr>
				<tr>
					<td><b>Answered By:</b></td>
					<td>".$fname." ".$mname." ".$lname."</td>
				</tr>
				<tr>
					<td><b>Student No.:</b></td>
					<td>".$student_id."</td>
				</tr>
				<tr>
					<td><b>Course&Section:</b></td>
					<td>".$course_prefix ." ".$section."</td>
				</tr>
			</table>
		</div>
		";
	}

	return $neck;
}

function produceBody(){
	$body = '';

	$database = new Database();
	$db = $database->connect();
	$quizModel = new Quiz($db);

	$body .="<div class='partDiv'>
					<div class='partTitleMar'>
						<b style='font-size:20px;'>Answers</b>
					</div>
							<table class='tableMar'>
							<tr>
								<th style='width:60%;'>Question</th>
								<th>Answer</th>
								<th style='width:10%;'>Status</th>
							</tr>
						";

    $body .= $_GET['room_id'];

    $result = $quizModel->getStudentsAnswer($_GET['quiz_id'],$_GET['room_id'],$_GET['user_id']);
	while($studinfo = $result->fetch(PDO::FETCH_OBJ)){
		if($studinfo->status == 1){$studinfo->status = "&#x2713;";}
		else{$studinfo->status = "&#x3A7;";}
		$body.="<tr>
					<td>".$studinfo->question."</td>
					<td>".$studinfo->answer."</td>
					<td>".$studinfo->status."</td>

				</tr>";
	}
	$body.="</table></div>";
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

$database = new Database();
$db = $database->connect();
$quizModel = new Quiz($db);

$damn = $quizModel->whoPrinted($_GET['admin_id']);

$mpdf->SetHTMLFooter('
	</br>
<table width="100%">
    <tr>
        <td width="33%" style="font-weight:bold; font-size:10px;">Date Printed:{DATE m-d-Y}</td>
        <td width="33%" align="center" style="font-weight:bold; font-size:10px;">{PAGENO}/{nbpg}</td>
        <td width="33%" style="text-align: right; font-weight:bold; font-size:10px;">Printed By:'.$damn.'</td>
    </tr>
</table>');


$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($head);

$theNeck = produceNeck();
$mpdf->WriteHTML($theNeck);

$theBodeh = produceBody();
$mpdf->WriteHTML($theBodeh);

$mpdf->Output();

?>