/**
 * Reads through document for all instances of a given HTML element and returns all the classes found within
 * the given element in a single array of strings.
 *
 * @param elem The HTML element to search for. Note: Just input the actual element name without the <> symbols.
 * @return A single array of all classes found
 *
 */
function getClasses(elem) {
	var inputElemClasses = ""; //String that will hold all classes found. Will later be split() into an array
	var numFoundElements = 0; //Will hold number of found html elements found in document
	var classesArray = new Array();
	
	//Loops until all given HTML elements found
	while (document.getElementsByTagName(elem)[numFoundElements] != null) {
		if (numFoundElements == 0) {
			inputElemClasses += document.getElementsByTagName(elem)[numFoundElements].className;
		}
		else {
			inputElemClasses += " " + document.getElementsByTagName(elem)[numFoundElements].className; //Same as above, except adds whitespace in beginning of string for the split function later on
		}
		
		numFoundElements++;
	}
	
	classesArray = inputElemClasses.split(" "); //Splits string into an array, delimited by single white space
	return classesArray;
}

/**
 * Adds a class to given element's class list. If element does not have pre-existing class attribute,
 * will create one.
 *
 * @param elem - The HTML element to add the new class to.
 * @param className - The class or classes to add to the HTML element
 */
function addClass(elem, className) {
	var newClass = document.getElementsByTagName(elem)[0];
	
	if (newClass.className != "") { //If there are existing classes present
		newClass.className = newClass.className + " " +	className;
	}
	else {
		newClass.className = className; //Preceding whitespace not needed when there are no existing classes
	}

}


/**
 * Validates form data from demo_form.html
 * Will highlight form fields in red that have errors. Will also print error out on top of page.
 * 
 * @return true - If no errors found
 * @return false - Errors found. Note: False return will prevent form from submitting to action url.
 */
function validateForm(){
	var noErrorsFound = true; //Return value. If false, errors were found and form will not submit to action url.
	var name = document.forms["myForm"]["name"].value;
	var email = document.forms["myForm"]["email"].value;
	var password = document.forms["myForm"]["password"].value;
	var confirm = document.forms["myForm"]["confirm"].value;
	
	//Clear all previous errors in the case where form is submitted multiple times. Otherwise error messages will just continue to concatenate.
	document.getElementById("errors").innerHTML = "";
	//Clear previous red colored forms
	document.forms["myForm"]["name"].setAttribute("style", "background-color: transparent");
	document.forms["myForm"]["email"].setAttribute("style", "background-color: transparent");
	document.forms["myForm"]["password"].setAttribute("style", "background-color: transparent");
	document.forms["myForm"]["confirm"].setAttribute("style", "background-color: transparent");
	
	//Check that all fields are not empty
	if (name.length == 0) {
		document.forms["myForm"]["name"].setAttribute("style", "background-color: red");
		document.getElementById("errors").innerHTML += "Error: Name field is empty.<br />";
		noErrorsFound = false; //This is important. It will prevent form from submitting to its action url.
	}
	
	if (email.length == 0) {
		document.forms["myForm"]["email"].setAttribute("style", "background-color: red");
		document.getElementById("errors").innerHTML += "Error: Email field is empty.<br />";
		noErrorsFound = false;
	}
	
	if (password.length == 0) {
		document.forms["myForm"]["password"].setAttribute("style", "background-color: red");
		document.getElementById("errors").innerHTML += "Error: Password field is empty.<br />";
		noErrorsFound = false;
	}
	
	if (confirm.length == 0) {
		document.forms["myForm"]["confirm"].setAttribute("style", "background-color: red");
		document.getElementById("errors").innerHTML += "Error: Password Confirm field is empty.<br />";
		noErrorsFound = false;
	}
	
	//Check that two passwords are the same
	if (password != confirm) {
		document.forms["myForm"]["password"].setAttribute("style", "background-color: red");
		document.forms["myForm"]["confirm"].setAttribute("style", "background-color: red");
		document.getElementById("errors").innerHTML += "Error: Password confirmation does not match.<br />";
		noErrorsFound = false;
	}
	
	
	return noErrorsFound;
}