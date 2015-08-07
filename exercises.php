<html>
<head>
<title>Practical exercises</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<?php
include('menu.php');
include('db.php');
session_start();

echo '<div id="content">';

//If user is not authorised
if(!isset($_SESSION['userPermission'])){
	header('HTTP/1.0 403 Forbidden');
	echo '<h1>403 Error</h1>You are forbidden!';
	die();
}
$permission=$_SESSION['userPermission'];
echo "<h1>Practical exercises</h1>";
//Query to retreive existing files from DB
$sql = "SELECT practEx FROM eLearningCourses WHERE id='$_SESSION[course_selection]'";
$mysqli->real_query($sql);
$res = $mysqli->store_result();
$num = $res->num_rows;

if($num > 0){
	while ($row = $res->fetch_assoc()) {
		//If there is no pdf uploaded
		if($row['practEx'] == ''){
			//If user is redirected from upload with error
			if(isset($_SESSION['upload'])&&$_SESSION['upload'] != "success"){
				echo "<div class='error'>Error occured while uploading file! $_SESSION[upload]</div><br>";
				unset($_SESSION['upload']);
			}
			//No available file(student)
			if($permission == 1){
				echo "There are no practical exercises available for this course right now. Please check again later.";
			}
			//No available file(teacher/admin)
			if($permission == 2||$permission == 3){
				echo "
				<form action='upload.php' method='post' enctype='multipart/form-data'>
				Select PDF	 to upload:
				<input type='file' name='fileToUpload' id='fileToUpload' accept='.pdf'><br>
				<input type='submit' value='Upload PDF' name='submit'>
				</form>
				";
			}
		}
		//If pdf exists
		else{
			//If user is redirected from successful upload
			if(isset($_SESSION['upload'])&&$_SESSION['upload'] == "success"){
				echo "<div class='success'>File uploaded successfully!</div><br>";
				unset($_SESSION['upload']);
			}
			//Display download for everyone
			echo "Download practical exercises: <form method='get' action='" . $row['practEx'] . "'>\n";
			echo "<button type='submit'>Download!</button></form>";
			//Display delete for teacher/admin
			if($permission == 2||$permission == 3){
				echo "Delete this file: <form method='get' action='delete.php'>
				<button type='submit'>Delete!</button></form>";
			}
			//Display shameless advert for everyone
			echo"
			Having trouble viewing the file? Try downloading Adobe Reader.<br>
			<a href='http://www.adobe.com/go/getreader'>
			<img border='0'  src='img/GetAdobeReader.png'>
			</a>";
		}
	}
}





?>
</div>
</html>
