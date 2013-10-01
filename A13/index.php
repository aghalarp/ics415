<!DOCTYPE html>
<html>
<head>
<title>A13</title>
  
<script>
/**
 * Validates form data
 * Will highlight form fields in red that have errors. Will also print error out on top of page.
 * 
 * @return true - If no errors found
 * @return false - Errors found. Note: False return will prevent form from submitting to action url.
 */
function validateForm(){
	var noErrorsFound = true; //Return value. If false, errors were found and form will not submit to action url.
	var name = document.forms["myForm"]["name"].value;
	var comment = document.forms["myForm"]["comment"].value;
	
	//Clear all previous errors in the case where form is submitted multiple times. Otherwise error messages will just continue to concatenate.
	document.getElementById("errors").innerHTML = "";
	//Clear previous red colored forms
	document.forms["myForm"]["name"].setAttribute("style", "background-color: transparent");
	document.forms["myForm"]["comment"].setAttribute("style", "background-color: transparent");
	
	//Check that all fields are not empty
	if (name.length == 0) {
		document.forms["myForm"]["name"].setAttribute("style", "background-color: red");
		document.getElementById("errors").innerHTML += "Error: Name field is empty.<br />";
		noErrorsFound = false; //This is important. It will prevent form from submitting to its action url.
	}
	
	if (comment.length == 0) {
		document.forms["myForm"]["comment"].setAttribute("style", "background-color: red");
		document.getElementById("errors").innerHTML += "Error: Comment field is empty.<br />";
		noErrorsFound = false;
	}
  
	return noErrorsFound;
}
</script>
</head>

<body>
  <?php
  //Check if both fields contain data (kind of redundant because JavaScript is checking this too, but oh well). Then write to file. 
  if(isset($_POST['name']) && isset($_POST['comment'])){
    $name =  $_POST['name'];
    $comment = $_POST['comment'];
    $postString = $name . ": " . $comment;
    
    $handle = fopen("filewrite.txt", "a+"); //a+ indicates write at end of file
    fwrite($handle, $postString . "\n");
  }
  
  //Read file
  if(file_exists("filewrite.txt")){
    $linesArray = file("filewrite.txt");
    
    echo "<table border='1'><tr><th>Comments</th></tr>";
    foreach ($linesArray as $line) {
      echo "<tr><td>" . $line . "</td></tr>";
    }
    echo "</table>";
  }
  ?>
  
  <p id="errors"></p>
  
  <form name="myForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateForm()" method="post">
  Name: <input type="text" name="name"><br/>
  Comment: <input type="text" name="comment"><br/>
  <input type="submit" value="Submit">
  </form>
  
  
</body>

</html>