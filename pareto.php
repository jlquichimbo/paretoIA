<?php

//echo 'Controlador';
//getData();
uploadExcel();

function uploadExcel() {
    $archivo = $_FILES['excel']['name'];

    $tipo = $_FILES['excel']['type'];

    $destino = "uploads/bak_" . $archivo;

    if (copy($_FILES['excel']['tmp_name'], $destino)) {

//        echo "Archivo Cargado Con Éxito";
    } else {
        echo "Error Al Cargar el Archivo";
    }
////////////////////////////////////////////////////////

    if (file_exists($destino)) {
        /** Clases necesarias */
        require_once('Classes/PHPExcel.php');
        require_once('Classes/PHPExcel/Reader/Excel2007.php');

// Cargando la hoja de cálculo
        $objReader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load($destino);
        $objFecha = new PHPExcel_Shared_Date();
        $arrayEtiquetas = array();
        $arrayValor = array();
        $arrayFrecuencia = array();
        $arrayPorcentaje = array();
        $array80 = array();

// Asignar hoja de excel activa
        $objPHPExcel->setActiveSheetIndex(0);
        //Generamos la tabla html
        $table = '<table class="table">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th>Variable</th>';
        $table .= '<th>Valor</th>';
        $table .= '<th>Acumulate</th>';
        $table .= '<th>Frecuency Acumulate</th>';
        $table .= '<th>80/20</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';

        $frecuencia = 0;
        //Obtenemos la ultima frecuencia
        for ($x = 2; $x <= $objPHPExcel->getActiveSheet()->getHighestRow(); $x++) {
//                $variable = get_value_xls($objPHPExcel, 0, $x); 
            $valor = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $x)->getCalculatedValue();
            $frecuencia += $valor;

            //Almacenamos el valor de la ultima frecuencia
            if ($x == $objPHPExcel->getActiveSheet()->getHighestRow()) {//ultima fila
                $ultimaFrecuencia = $frecuencia;
            }
        }

        $frecuencia = 0;
        //Generamos los registros
        for ($x = 2; $x <= $objPHPExcel->getActiveSheet()->getHighestRow(); $x++) {
            $table .= '<tr>';
            $variable = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $x)->getCalculatedValue();
//                $variable = get_value_xls($objPHPExcel, 0, $x); 
            $valor = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $x)->getCalculatedValue();
            $frecuencia += $valor;
            $table .= '<td>' . $variable . '</td>';
            $table .= '<td>' . $valor . '</td>';
            $porcentaje = ($frecuencia / $ultimaFrecuencia) * 100;
            $table .= '<td>' . $frecuencia . '</td>';//Frecuencia
            $table .= '<td>' . $porcentaje . '</td>';//Porcentaje
            $table .= '<td>' . '80' . '</td>';
            $table .= '</tr>';
            //Armamos el array labels
            $arrayEtiquetas[] = $variable;
            //Armamos el array valor
            $arrayValor[] = $valor;
            //Armamos el array frecuencia
            $arrayFrecuencia[] = $frecuencia;
            //Armamos el array porcentaje
            $arrayPorcentaje[] = $porcentaje;
            //Armamos el array 80/20
            $array80[] = 80;
        }
        $table .= '</tbody>';
        $table .= '</table>';
        viewResults($table, $arrayEtiquetas, $arrayValor, $arrayFrecuencia, $arrayPorcentaje, $array80);
//        echo $table;
    }
}

function viewResults($table, $arrayEtiquetas, $arrayValor, $arrayFrecuencia, $arrayPorcentaje, $array80) {
    include ('resultsView.php');
}
