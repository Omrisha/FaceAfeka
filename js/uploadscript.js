$(document).ready(() => {
    console.log("ready");
    $('#file').on('change', () => {
        console.log("changed");
        var countFiles = document.getElementById('file').files.length;
        if (countFiles > 6) {
          alert("You can upload up to six photos!");
        }
        for (var i = 0; i < countFiles; i++) {
          var property = document.getElementById('file').files[i];
          var imageName = property.name + '';
          var imageExt = imageName.split(".").pop().toLowerCase();

          if ($.inArray(imageExt, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
              alert("Invalid image file");
          }

          var imageSize = property.size;
          if (imageSize > 2000000) {
            alert("Exceeded image size of 2MB.");
          } else {
              var formData = new FormData();
              formData.append("file", property);

              $.ajax({
                  url: "/php/upload-photo.php?id="+i,
                  method: "POST",
                  data: formData,
                  contentType: false,
                  cache: false,
                  processData: false,
                  beforeSend: function() {
                      $('#imgFile').html("Image uploading....");
                  },
                  success: function(data) {
                    $('#imgFile').html("Upload success");
                    $('#photos').append(data);
                  }
              });
          }
        }
      });
  });