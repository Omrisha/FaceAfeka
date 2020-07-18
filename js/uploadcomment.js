// $(document).ready(() => {
//     console.log("doc post ready");
//     $('#comment').on('click', () => {
        
//     });
// });
function addComment(id) {
    var content = $('#commentcontent'+id).val();
        var id = $('#commentcontent'+id).attr('name');
        console.log(id);

        var formData = new FormData();
        formData.append("content", content);

        $.ajax({
            url: "/php/create-comment.php?postid="+id,
            method: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                window.location.replace(data);
            }
        });
}
