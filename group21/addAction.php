<html>
<head>
	<title>Add Data</title>
</head>

<body>
<?php
// Include the database connection file
require_once("dbConnection.php");

if (isset($_POST['submit'])) {
	// Escape special characters in string for use in SQL statement	
	$studentnumber = mysqli_real_escape_string($con, $_POST['studentnumber']);
	$name = mysqli_real_escape_string($con, $_POST['name']);
	$email = mysqli_real_escape_string($con, $_POST['email']);
	$mobilenumber = mysqli_real_escape_string($con, $_POST['mobilenumber']);
		
	// Check for empty fields
	if (empty($name) || empty($mobilenumber) || empty($email) || empty($studentnumber)) {
		if (empty($studentnumber)) {
			echo "<font color='red'>Student Number field is empty.</font><br/>";
		}
		if (empty($name)) {
			echo "<font color='red'>Name field is empty.</font><br/>";
		}
		
		if (empty($mobilenumber)) {
			echo "<font color='red'>Mobile Number field is empty.</font><br/>";
		}
		
		if (empty($email)) {
			echo "<font color='red'>Email field is empty.</font><br/>";
		}
		
		// Show link to the previous page
		echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
	} else { 
		// If all the fields are filled (not empty) 

		// Insert data into database
		$result = mysqli_query($con, "INSERT INTO students (`studentnumber`, `name`, `mobilenumber`, `email`) VALUES ('$studentnumber','$name', '$mobilenumber', '$email')");
		
		// Display success message
		echo "<p><font color='green'>Data added successfully!</p>";
		echo "<a href='index.php'>View Result</a>";
	}
}
?>
</body>
</html>
