$(document).ready(() => {
  console.log("ready");
  $("#search_text").keyup(() => {
    var text = $("#search_text").val();
    console.log(text);
    if (text != '' ){
      $.ajax({
        url: "/php/search.php?query=" + text,
        method: "GET",
        dataType: "text",
        success: function(data){
          $('#result').html(data);
        }
      })
    } else {
      $('#result').html('');
    }
  });
});