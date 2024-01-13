<?php

namespace App\Exports;

//Personalizar trabajando con macros
use \Maatwebsite\Excel\Sheet;

//Para logo
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

//Permite personalizar bordes, rellenos, estilos de fuentes
use \PhpOffice\PhpSpreadsheet\Style\Border;
use \PhpOffice\PhpSpreadsheet\Style\Fill;
use \PhpOffice\PhpSpreadsheet\Style\Font;
use \PhpOffice\PhpSpreadsheet\Style\NumberFormat;

//ALTERNATIVA A AJUSTAR TEXTO VERTICALMENTE A SU ALTURA (Funciona en MS-Excel pero no en LibreOffice)
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// $spreadsheet = new Spreadsheet();
// foreach ($spreadsheet->getActiveSheet()->getRowDimensions() as $rowID) {
//     $rowID->setRowHeight(-1);
// }

//################################## COLORES POR DEFECTO ##################################
//==========> BORDES
$colorBordeDelagado = '000000';
$colorBordeGrueso = '000000';
//==========> FILA NOMBRE DE LA EMPRESA Y LOGO
$colorLetraNombreEmpresa = '000000';
$colorFondoNombreEmpresa = 'F2D69D';
//==========> FILA TÍTULO
$colorLetraTitulo = '3F3F3F';
$colorFondoTituloArriba = 'F2D69D';
$colorFondoTituloMedio = 'ffffff';
$colorFondoTituloAbajo = 'ffffff';
//==========> FILA LUGAR Y/O FECHA
$colorLetraLugarFecha = '555555';
//==========> FILA CABECERA DE TÍTULOS DE LA TABLA
$colorLetraTablaCabecera = 'ffffff';
$fondoTablaCabecera = 'f6b532';
//==========> FILAS y COLUMNAS DEL CUERPO DE LA TABLA
$fondoCuerpoClaro = 'ffffff';
$fondoCuerpoOscuro = 'f6f6f6';


//################################### SECCIÓN MACROS ######################################
//########## SIMPLIFICA EL LLAMADO A LAS FUNCIONES PARA PERSONALIZAR LAS CELDAS ###########

//========================================= BORDES ========================================
Sheet::macro('estiloBordeGruesoExterno', function (Sheet $sheet, string $rangoCeldas, string $color) {
    $sheet->getDelegate()->getStyle($rangoCeldas)->applyFromArray([
        'borders' => [
            'outline' => [
                'borderStyle' => Border::BORDER_THICK,
                'color' => ['argb' => $color]
            ]
        ]
    ]);
});

Sheet::macro('estiloBordesDelgadoInterno', function (Sheet $sheet, string $rangoCeldas, string $color) {
    $sheet->getDelegate()->getStyle($rangoCeldas)->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => $color]
            ]
        ]
    ]);
});


//============================== RELLENOS DE COLOR DE CELDA ===============================
//Relleno color sólido
Sheet::macro('estiloRellenarColor', function (Sheet $sheet, string $rangoCeldas, string $color) {
    $sheet->getStyle($rangoCeldas)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($color);
});

//Relleno color matizado (De arriba a abajo)
Sheet::macro('estiloRellenarColorGradiente', function (Sheet $sheet, string $rangoCeldas, string $colorArriba, string $colorAbajo) {
    $sheet->getStyle($rangoCeldas)->getFill()->applyFromArray([
        'fillType' => Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startColor' => ['rgb' => $colorArriba],
        'endColor' => ['argb' => $colorAbajo]
    ]);
});


//============================ ALINEACIÓN y AJUSTE DE TEXTOS ==============================
//Centrar texto horizontalmente dentro de una celda
Sheet::macro('estiloCentrar', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getDelegate()->getStyle($rangoCeldas)->getAlignment()->applyFromArray([
        'horizontal' => 'center'
    ]);
});

//Centrar texto verticalmente dentro de una celda
Sheet::macro('estiloCentrarVerticalmente', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getDelegate()->getStyle($rangoCeldas)->getAlignment()->applyFromArray([
        'vertical' => 'center'
    ]);
});

//Justificar texto a la derecha dentro de una celda
Sheet::macro('estiloJustificarDerecha', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getDelegate()->getStyle($rangoCeldas)->getAlignment()->applyFromArray([
        'horizontal' => 'right'
    ]);
});

//Justificar texto a la izquierda dentro de una celda
Sheet::macro('estiloJustificarIzquierda', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getDelegate()->getStyle($rangoCeldas)->getAlignment()->applyFromArray([
        'horizontal' => 'left'
    ]);
});

//Justificar texto dentro de una celda
Sheet::macro('estiloJustificarTexto', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getDelegate()->getStyle($rangoCeldas)->getAlignment()->applyFromArray([
        'horizontal' => 'justify'
    ]);
});

//Ajusta el texto dentro de la celda
Sheet::macro('estiloAjustarTexto', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getDelegate()->getStyle($rangoCeldas)->getAlignment()->setWrapText(true);
});

//Ajustar el texto verticalmente dentro de la celda (Funciona en MS-Excel pero no en LibreOffice)
Sheet::macro('estiloAjustarTextoVerticalmente', function (Sheet $sheet, int $numeroFila) {
    $sheet->getRowDimension($numeroFila)->setRowHeight(-1);
});


//======================== ALTURA DE FILAS Y ANCHO DE COLUMNAS ============================
//Altura de una fila en específico
Sheet::macro('estiloAlturaFila', function (Sheet $sheet, int $numeroFila, int $altura) {
    $sheet->getRowDimension($numeroFila)->setRowHeight($altura);
});

//Ancho de una columna en específico
Sheet::macro('estiloAnchoColumna', function (Sheet $sheet, string $letraColumna, int $ancho) {
    $sheet->getColumnDimension($letraColumna)->setWidth($ancho);
});


//========================== TAMAÑO Y TIPO DE FUENTES Y/O LETRAS ==========================
Sheet::macro('estiloLetraCalibriTam', function (Sheet $sheet, string $rangoCeldas, int $tam) {
    $sheet->getDelegate()->getStyle($rangoCeldas)->applyFromArray([
        'font' => [
            'name' => 'Calibri',
            'size' => $tam
        ]
    ]);
});

Sheet::macro('estiloLetraColor', function (Sheet $sheet, string $rangoCeldas, string $color) {
    $sheet->getStyle($rangoCeldas)->getFont()->getColor()->applyFromArray(['rgb' => $color]);
});


//=========================== ESTILOS DE TEXTO NEGRITA, CURSIVA ===========================
//Establecer estilo negrita en fuente
Sheet::macro('estiloNegrita', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getDelegate()->getStyle($rangoCeldas)->applyFromArray([
        'font' => [
            'bold' => true
        ]
    ]);
});

//Establecer estilo cursiva en fuente
Sheet::macro('estiloCursiva', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getDelegate()->getStyle($rangoCeldas)->applyFromArray([
        'font' => [
            'italic' => true
        ]
    ]);
});

//Establecer estilo subrallado en fuente
Sheet::macro('estiloSubrallado', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getDelegate()->getStyle($rangoCeldas)->applyFromArray([
        'font' => [
            'underline' => Font::UNDERLINE_SINGLE
        ]
    ]);
});

//Establecer estilo subrallado en fuente
Sheet::macro('estiloSubralladoDoble', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getDelegate()->getStyle($rangoCeldas)->applyFromArray([
        'font' => [
            'underline' => Font::UNDERLINE_DOUBLE
        ]
    ]);
});

//==================================== TIPOS DE CAMPO =====================================
/*Nota: Para las siguientes funciones los campos deben ser previamente datos
de tipo numérico y además los td de donde son recuperados dichos campos no deben
estar rodeados por etiquetas <b> u otras*/

//Dar formato a campo número con dos decimales.
//Ejemplo 12345.6 lo transforma a 12435.60
Sheet::macro('estiloNumeroADosDecimales', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getStyle($rangoCeldas)->getNumberFormat()->setFormatCode('0.00');
});

//Dar formato a campo número con separadores de miles y dos decimales.
//Ejemplo 123456789123.45 lo transforma a 123,456,789,123.45
//NOTA: Número máximo al que es aplicable: 999999999999.99
Sheet::macro('estiloSeparadorMilesDosDecimales', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getStyle($rangoCeldas)->getNumberFormat()->setFormatCode('###,###,###,##0.00');
});

//=================================== TIPOS DE FORMATOS ===================================
//Establecer formato general (Por defecto)
Sheet::macro('estiloFormatoGeneral', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getStyle($rangoCeldas)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
});

//Establecer formato de texto (Útil para evitar que números enteros se expresen con potencias de 10)
Sheet::macro('estiloFormatoTexto', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getStyle($rangoCeldas)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
});

//Establecer formato numérico
Sheet::macro('estiloFormatoNumero', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getStyle($rangoCeldas)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
});

//Establecer formato numérico con separador de miles
Sheet::macro('estiloFormatoSeparadorMiles', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getStyle($rangoCeldas)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
});

//Establecer formato en moneda Dólar
Sheet::macro('estiloFormatoMonedaUSD', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getStyle($rangoCeldas)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD);
});

//Establecer formato en moneda Euro
Sheet::macro('estiloFormatoMonedaEUR', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getStyle($rangoCeldas)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_EUR);
});

//Establecer formato en porcentaje
Sheet::macro('estiloFormatoPorcentaje', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getStyle($rangoCeldas)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
});

//Establecer formato en fecha dd/mm/yyyy
Sheet::macro('estiloFormatoFecha', function (Sheet $sheet, string $rangoCeldas) {
    $sheet->getStyle($rangoCeldas)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
});

//####################################### FIN MACROS ######################################
