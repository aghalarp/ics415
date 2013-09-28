$(document).ready(function() {
  var clickedSubmitBtn = "";
  $(document).on("click", ":submit", function(e){
    clickedSubmitBtn = $(this).attr("id"); //Detects which submit button was clicked
  });
  
  
  $("#input").submit(function(event) {
    var url = $("#url").val(); //Gets submitted URL
    if(clickedSubmitBtn == "getTags") { //Lists all elements and their appearance amount in a simple table
      $.get(url, function(data) {
        var containerHtml = $("<div id='htmlcontainer'></div").html(data); //This is needed in order to show results from both HEAD and BODY sections. If we just use "data", will only show BODY contents

        var retrieved = $("*", $(containerHtml)); //Grabs ALL elements, as denoted by the asterisk*
        var elements = {}; //Note, this is an object, not array. Using it as a pseudo associative array
        
        for (var i = 0; i < retrieved.length; i++) {
          var temp = retrieved.eq(i).prop("tagName");
          if (elements[temp] == null){
            elements[temp] = 1;
          }
          else {
            elements[temp]++;
          }
        }

        var table = $("<table id='ListElements' border='1'><tr><th>Tag</th><th>Count</th></tr></table>");
        $("#input").append(table);
         
        $.each(elements , function(key, val) { 
          var row = "<tr><td>" + key + "</td><td>" + val + "</td></tr>";
          $("#ListElements tr:last").after(row); //Selects last tr in table and inserts row string after
        });
      });
    }
    else if (clickedSubmitBtn == "getJs") { //Detect and display names of loaded External Javascript files
      $.get(url, function(data) {
        var containerHtml = $("<div id='htmlcontainer'></div").html(data); //This is needed in order to show results from both HEAD and BODY sections. If we just use "data", will only show BODY contents

        var retrieved = $("script", $(containerHtml)); //Grabs all "SCRIPT" tags
        var jsfiles = [];
       
        for (var i = 0; i < retrieved.length; i++) {
          var temp = retrieved.eq(i).prop("src");
          jsfiles[i] = temp;
        }

        var table = $("<table id='ListJS' border='1'><tr><th>External JavaScript Files</th></tr></table>");
        $("#input").append(table);
         
        $.each(jsfiles , function(key, val) { 
          var row = "<tr><td>" + val + "</td></tr>";
          $("#ListJS tr:last").after(row); //Selects last tr in table and inserts row string after
        });
      });
     
    }
    
    event.preventDefault(); //Stops page from actually submitting, which would reload the page before showing the script's output           
  });
  
});
