$(document).ready(() => {
    console.log("doc post ready");
    $('#post').on('click', () => {
        var img1 = $('#0').attr('src') === undefined ? "" : $('#0').attr('src');
        var img2 = $('#1').attr('src') === undefined ? "" : $('#1').attr('src');
        var img3 = $('#2').attr('src') === undefined ? "" : $('#2').attr('src');
        var img4 = $('#3').attr('src') === undefined ? "" : $('#3').attr('src');
        var img5 = $('#4').attr('src') === undefined ? "" : $('#4').attr('src');
        var img6 = $('#5').attr('src') === undefined ? "" : $('#5').attr('src');
        var content = $('#content').val();
        console.log(content);

        var formData = new FormData();
              formData.append("img1", img1);
              formData.append("img2", img2);
              formData.append("img3", img3);
              formData.append("img4", img4);
              formData.append("img5", img5);
              formData.append("img6", img6);
              formData.append("content", content);

              $.ajax({
                  url: "/php/create-post.php",
                  method: "POST",
                  data: formData,
                  contentType: false,
                  cache: false,
                  processData: false,
                  success: function(data) {
                    window.location.replace(data);
                  }
              });
    });
});

