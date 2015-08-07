
<?php
include('db.php');

session_start();
$target_dir = "pdf/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if file already exists
if (file_exists($target_file)) {
	$uploadOk = 0;
	$_SESSION['upload'] = "Sorry, file already exists.";
}
// Check if file size is less then 5MB
if ($_FILES["fileToUpload"]["size"] > 5242880) {
	$_SESSION['upload'] = "Sorry, your file is too large.";
	$uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "pdf" ) {
	$_SESSION['upload'] = "Sorry, only PDF files are allowed.";
	$uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
	header('Location: exercises.php');
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		$sql = "UPDATE eLearningCourses SET practEx='$target_file' WHERE id='$_SESSION[course_selection]'";
		if ($mysqli->query($sql) === TRUE) {
			$_SESSION['upload'] = "success";
			header('Location: exercises.php');
		} else {
			echo "Error updating record: " . $conn->error;
		}
    } else {
       			$_SESSION['upload'] = "Unknown error.";
			header('Location: exercises.php');
    }
}
?>
