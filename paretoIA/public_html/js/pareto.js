/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$('#btn_cargar').click(function(event){
    event.preventDefault();//Detiene el post
    getXls();
});
function getXls(){
    var oFileIn;
    filePicked();
    
//    oFileIn = document.getElementById('input_xls');
//    if(oFileIn.addEventListener) {
//        oFileIn.addEventListener('change', filePicked, false);
//    }

}


function filePicked(oFileIn) {
    console.log('recorriendo excel');
    // Get The File From The Input
//    var oFile = oEvent.target.files[0];
//    var sFilename = oFile.name;
    // Create A File Reader HTML5
    var reader = new FileReader();
    
    // Ready The Event For When A File Gets Selected
    reader.onload = function(e) {
        var data = e.target.result;
        var cfb = XLS.CFB.read(data, {type: 'binary'});
        var wb = XLS.parse_xlscfb(cfb);
        // Loop Over Each Sheet
        wb.SheetNames.forEach(function(sheetName) {
            // Obtain The Current Row As CSV
            var sCSV = XLS.utils.make_csv(wb.Sheets[sheetName]);   
            var data = XLS.utils.sheet_to_json(wb.Sheets[sheetName], {header:1});   
            $.each(data, function( indexR, valueR ) {
                var sRow = "<tr>";
                $.each(data[indexR], function( indexC, valueC ) {
                    sRow = sRow + "<td>" + valueC + "</td>";
                });
                sRow = sRow + "</tr>";
                $("#my_file_output").append(sRow);
                console.log(sRow);
                
            });
            
        });
    };
    reader.readAsBinaryString(oFile);

    // Tell JS To Start Reading The File.. You could delay this if desired
}