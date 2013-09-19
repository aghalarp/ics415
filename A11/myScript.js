$(document).ready(function() {
  
  //First, hide all answer classes on document load
  $(".answer").hide();
  
  //Whenever a question class is clicked, will find the next list element, which should be the "answer", and apply the slideToggle effect.
  $(".question").click(function() {
    $(this).next().slideToggle(300);
    
    //"Toggles" +/- sign
    if ($(this).children("span").html() == "+") {
      $(this).children("span").html("-");
    }
    else if ($(this).children("span").html() == "-") {
      $(this).children("span").html("+");
    }
    
  });

});