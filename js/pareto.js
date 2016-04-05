$('#btn_cargar').click(function (event) {
//    event.preventDefault();//Detiene el post
//    getXls();
});

function getXls() {
    var url = 'pareto.php';
//    excel =  new FormData($("formformuploadajaxid")[0]);
    var formData = new FormData(document.getElementById("formuploadajax"));
    $.ajax({
        url: url,
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log(data);
            $('#my_file_output').html(data);
        },
        error: function () {
        }
    });

}
