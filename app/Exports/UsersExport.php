<?php

namespace App\Exports;
//Creación desde una vista Blade
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
//Para personalizar los estilos en la exportación
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
class UsersExport implements FromView, WithEvents, WithDrawings
{
    public function drawings(){
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/logo.png'));
        $drawing->setHeight(70);
        $drawing->setOffsetY(8);
        $drawing->setOffsetX(10); //Desfasa la imagen 35 px desde la izquierda (Para aparantar un centrado horizontal de la imagen)
        $drawing->setCoordinates('A1');

        return $drawing;
    }
    //Para obtener parámetros desde el controlador, desde el cual es instanciado el Export
    public function parametros($usuarios){
        $this->usuarios = $usuarios;
        return $this;
    }

    public function view(): View{
        //Se pasa los parámetros a la vista que dibuja la estructura de la tabla
        $usuarios = $this->usuarios;
        return view('users.excel', compact('usuarios'));
    }

    //Para dar formato y estilos a las celdas
    public function registerEvents(): array{
        return [
            AfterSheet::class => function (AfterSheet $event) {
                //#################################################################################################
                //###### IMPORTAR MACROS PERSONALIZADAS PARA APLICAR ESTILOS, BORDES, CENTREADOS, COLOR, ETC ######
                //####################### (TAMBIÉN INCLUYE LOS COLORES POR DEFECTO A USAR) ########################
                include("ExcelMacrosExport.php");

                //########################## CÁLCULO DE FILAS Y COLUMNAS DE INICIO Y FIN ##########################

                $primeraColumna = 'A';
                $primeraFila = 1;
                $primeraFilaTabla = 3;
                $primeraFilaDatos = 4;

                $ultimaColumna = $event->sheet->getHighestColumn();
                $ultimaFilaDatos = $event->sheet->getHighestRow();

                $inicioRango = $primeraColumna . $primeraFila;
                $finRango = $ultimaColumna . $ultimaFilaDatos;
                $rangoDatos = $primeraColumna . $primeraFilaDatos . ':' . $finRango;

                //############################## PERSONALIZAR CELDAS ###############################
                //==========> BORDES
                $event->sheet->estiloBordesDelgadoInterno($primeraColumna . $primeraFilaTabla . ':' . $finRango, $colorBordeDelagado);
                $event->sheet->estiloBordeGruesoExterno($inicioRango . ':' . $finRango, $colorBordeGrueso);

                //############################# PERSONALIZAR COLUMNAS ##############################
                //==========> ESTABLECER ANCHO DE LAS COLUMNAS
                $event->sheet->estiloAnchoColumna('A', 5);
                $event->sheet->estiloAnchoColumna('B', 35);
                $event->sheet->estiloAnchoColumna('C', 30);
                $event->sheet->estiloAnchoColumna('D', 10);
                $event->sheet->estiloAnchoColumna('E', 30);



                //==========> AJUSTE DEL CONTENIDO AL ANCHO DE LA CELDA (WRAP TEXT)
                $columnasPorAjustar = ['A', 'B', 'C', 'D', 'E'];
                foreach ($columnasPorAjustar as $columna) {
                    $event->sheet->estiloAjustarTexto($columna . $primeraFilaTabla . ':' . $columna . $ultimaFilaDatos);
                }

                //==========> FILA CABECERA DE TÍTULOS DE LA TABLA
                $numFila = $primeraFilaTabla;
                $fila = $primeraColumna . $numFila . ':' . $ultimaColumna . $numFila;
                //Centrar
                $event->sheet->estiloCentrar($fila);
                $event->sheet->estiloCentrarVerticalmente($fila);
                //Estilos
                $event->sheet->estiloNegrita($fila);
                //Color
                $event->sheet->estiloLetraColor($fila, $colorLetraTablaCabecera);
                $event->sheet->estiloRellenarColor($fila, $fondoTablaCabecera);

                //==========> FILAS y COLUMNAS DEL CUERPO DE LA TABLA
                //Centrar
                $columnasCentradas =  ['A','B','C','D','E'];
                foreach ($columnasCentradas as $columna) {
                    $event->sheet->estiloCentrar($columna . $primeraFilaDatos . ':' . $columna . $ultimaFilaDatos);
                }
                //Centrar Verticalmente
                $event->sheet->estiloCentrarVerticalmente($rangoDatos);
                //Justificar texto
                $columnasAJustificar = [];
                foreach ($columnasAJustificar as $columna) {
                    $event->sheet->estiloJustificarTexto($columna . $primeraFilaDatos . ':' . $columna . $ultimaFilaDatos);
                }

                //Tamaño letra
                $event->sheet->estiloLetraCalibriTam($rangoDatos, 10);
                //Color a rayas
                for ($i = $primeraFilaDatos; $i <= $ultimaFilaDatos; $i++) {
                    $fila = $primeraColumna . $i . ':' . $ultimaColumna . $i;
                    $color = ($i % 2 == 0) ?  $fondoCuerpoClaro : $fondoCuerpoOscuro;
                    $event->sheet->estiloRellenarColor($fila, $color);
                }

                ##AHORA CABECERA
                //################################### CABECERA #####################################
                //==========> FILA: NOMBRE DE LA EMPRESA Y LOGO
                $numFila = $primeraFila;
                //ULTIMA COLUMNA SE ACTUALIZA DESPUES DE OCULTAR TODAS LAS COLUMNAS NECESARIAS
                $ultimaColumna = $event->sheet->getHighestDataColumn();
                $fila = $primeraColumna . $numFila . ':' . $ultimaColumna . $numFila;
                $event->sheet->mergeCells($fila);
                //Altura
                $event->sheet->estiloAlturaFila(1, 20);
                //Estilos
                $event->sheet->estiloNegrita($fila);
                $event->sheet->estiloJustificarDerecha($fila);
                //Color
                $event->sheet->estiloLetraColor($fila, $colorLetraNombreEmpresa);
                $event->sheet->estiloRellenarColor($fila, $colorFondoNombreEmpresa);
                $numFila = $primeraFila + 1;

                //############################## PERSONALIZAR CELDAS ###############################
                $inicioRango = $primeraColumna . $primeraFila;
                $finRango = $ultimaColumna . $ultimaFilaDatos;
                $rangoDatos = $primeraColumna . $primeraFilaDatos . ':' . $finRango;
                //==========> BORDES
                $event->sheet->estiloBordesDelgadoInterno($primeraColumna . $primeraFilaTabla . ':' . $finRango, $colorBordeDelagado);
                $event->sheet->estiloBordeGruesoExterno($inicioRango . ':' . $finRango, $colorBordeGrueso);

                //ULTIMA COLUMNA SE ACTUALIZA DESPUES DE OCULTAR TODAS LAS COLUMNAS NECESARIAS
                $ultimaColumna = $event->sheet->getHighestDataColumn();
                $fila = $primeraColumna . $numFila . ':' . $ultimaColumna . $numFila;
                $event->sheet->mergeCells($fila);
                //Altura
                $event->sheet->estiloAlturaFila($numFila, 40);
                //Centrar
                $event->sheet->estiloCentrar($fila);
                $event->sheet->estiloCentrarVerticalmente($fila);
                //Estilos
                $event->sheet->estiloNegrita($fila);
                //$event->sheet->estiloSubrallado($fila);
                //Tamaño letra
                $event->sheet->estiloLetraCalibriTam($fila, 28);
                //Color
                $event->sheet->estiloLetraColor($fila, $colorLetraTitulo);
                $event->sheet->estiloRellenarColorGradiente($fila, $colorFondoTituloArriba, $colorFondoTituloAbajo);
            }
        ];
    }

}
