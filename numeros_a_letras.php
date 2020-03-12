<?php

/*
 * Convierte de numeros a letras en formato mexicano.
 * 
 * Util para formatos utilizados en la version impresa de facturas CFDI que pide el SAT
 * 
 * 
 * 
 * MONEDAS:
 * 
 * Basados en el Anexo20 del diario oficial del SAT conforme con la especificación ISO 4217.
 * 
 * http://omawww.sat.gob.mx/tramitesyservicios/Paginas/documentos/GuiaAnexo20.pdf
 * 
 * Ejemplo: MXN, EUR, USD ...
 * 
 * LIMITES:
 * 
 *    18 dígitos con 2 decimales
 *    Ejemplo: 999,999,999,999,999,999.99
 *    
 * DECIMANLES:
 * Al final del texto agregará la representacion de decimales como se usa en Mexico
 * 
 *       99/100 M.N.
 *
 * 
 * GNU GENERAL PUBLIC LICENSE
 * 
 * Created by: 	Hector Manuel Alonso Ortiz
 * eMail: 		alonso.hector@gmail.com
 * Github: 		https://github.com/alonsohector/numeros_a_letras
 *  * 
 */
 
/* numeros_a_letras  */


class numeros_a_leras {

    private $xarray = 
    array(
        0 => "Cero",
        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE", "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE", "VEINTI", 
       30 => "TREINTA", 
       40 => "CUARENTA", 
       50 => "CINCUENTA", 
       60 => "SESENTA", 
       70 => "SETENTA", 
       80 => "OCHENTA", 
       90 => "NOVENTA",
      100 => "CIENTO", 
      200 => "DOSCIENTOS", 
      300 => "TRESCIENTOS", 
      400 => "CUATROCIENTOS", 
      500 => "QUINIENTOS", 
      600 => "SEISCIENTOS", 
      700 => "SETECIENTOS", 
      800 => "OCHOCIENTOS", 
      900 => "NOVECIENTOS"
    );


    public function numtoletras($xcifra, $moneda)
    {
        
        //limpia espacios y quita los signos, para CFDI no se exigen los signos para CFDIs
        $xcifra = trim($xcifra);
        $xcifra = $this->quita_signos($xcifra);
        
        //revisa las posiciones para de manera general usar la informacion
        $xlength = strlen($xcifra);
        $xpos_punto = strpos($xcifra, ".");
        $xaux_int = $xcifra;
        $xdecimales = "00";
        if (!($xpos_punto === false)) {
            if ($xpos_punto == 0) {
                $xcifra = "0" . $xcifra;
                $xpos_punto = strpos($xcifra, ".");
            }
            $xaux_int = substr($xcifra, 0, $xpos_punto); // obtiene los enteros
            $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtiene los valores decimales
        }

        $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // se ajusta la longitud del numero, para que sea divisible por centenas de miles en grupos de 6
        $xcadena = "";
        for ($xz = 0; $xz < 3; $xz++) {
            $xaux = substr($XAUX, $xz * 6, 6);
            $xi = 0;
            $xlimite = 6; // contador de centenas xi y se establece el límite a 6 dígitos en la parte entera
            $xexit = true; // bandera para controlar el ciclo del While
            while ($xexit) {
                if ($xi == $xlimite) { // revisa si ya llegó al límite máximo de enteros
                    break; // sale el ciclo
                }

                $x3digitos = ($xlimite - $xi) * -1; // toma los tres primeros digitos del numero, comenzando por la izquierda
                $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // la centena (los tres dígitos)
                for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                    switch ($xy) {
                        case 1: // revisa las centenas
                            if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                                
                            } else {
                                $key = (int) substr($xaux, 0, 3);
                                if (TRUE === array_key_exists($key, $this->xarray)){  // revisa si la centena es un numero redondo (100, 200, 300, 400, etc..)
                                    $xseek = $this->xarray[$key];
                                    $xsub = $this->subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                    if (substr($xaux, 0, 3) == 100)
                                        $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                    $xy = 3; // la centena es una cantidad cerrada, entonces finaliza el ciclo del for y ya no revisa decenas ni unidades
                                }
                                else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                    $key = (int) substr($xaux, 0, 1) * 100;
                                    $xseek = $this->xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 0, 3) < 100)
                            break;
                        case 2: // revisa las decenas (con la misma lógica que las centenas)
                            if (substr($xaux, 1, 2) < 10) {
                                
                            } else {
                                $key = (int) substr($xaux, 1, 2);
                                if (TRUE === array_key_exists($key, $this->xarray)) {
                                    $xseek = $this->xarray[$key];
                                    $xsub = $this->subfijo($xaux);
                                    if (substr($xaux, 1, 2) == 20)
                                        $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                    $xy = 3;
                                }
                                else {
                                    $key = (int) substr($xaux, 1, 1) * 10;
                                    $xseek = $this->xarray[$key];
                                    if (20 == substr($xaux, 1, 1) * 10)
                                        $xcadena = " " . $xcadena . " " . $xseek;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 1, 2) < 10)
                            break;
                        case 3: // revisa las unidades
                            if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
                                
                            } else {
                                $key = (int) substr($xaux, 2, 1);
                                $xseek = $this->xarray[$key]; // obteiene directamente el valor de la unidad (del uno al nueve)
                                $xsub = $this->subfijo($xaux);
                                $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                            } // ENDIF (substr($xaux, 2, 1) < 1)
                            break;
                    } // END SWITCH
                } // END FOR
                $xi = $xi + 3;
            } // ENDDO

            if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
                $xcadena.= " DE";

            if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
                $xcadena.= " DE";

            // ----------- aqui se puede cambiar de acuerdo a las necesidades de representacion del país, por default es Mexico -------
            if (trim($xaux) != "") {
                switch ($xz) {
                    case 0:
                        if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                            $xcadena.= "UN BILLON ";
                        else
                            $xcadena.= " BILLONES ";
                        break;
                    case 1:
                        if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                            $xcadena.= "UN MILLON ";
                        else
                            $xcadena.= " MILLONES, ";
                        break;
                    case 2:
                        if ($xcifra < 1) {
                            if($moneda=="MXN"){
                                $xcadena = "CERO PESOS $xdecimales/100 M.N.";	
                            }
                            if($moneda=="USD"){
                                $xcadena = "CERO DÓLARES $xdecimales/100 USD";	
                            }
                            if($moneda=="EUR"){
                                $xcadena = "CERO EUROS $xdecimales/100 EUR";	
                            }
                            
                        }
                        if ($xcifra >= 1 && $xcifra < 2) {
                            if($moneda=="MXN"){
                                $xcadena = "UN PESO $xdecimales/100 M.N. ";
                            }
                            if($moneda=="USD"){
                                $xcadena = "UN DÓLAR $xdecimales/100 USD ";
                            }
                            if($moneda=="EUR"){
                                $xcadena = "UN EURO $xdecimales/100 EUR ";
                            }
                        }
                        if ($xcifra >= 2) {
                            if($moneda=="MXN"){
                                $xcadena.= " PESOS $xdecimales/100 M.N. "; //
                            }
                            if($moneda=="USD"){
                                $xcadena.= " DÓLARES $xdecimales/100 USD ";
                            }
                            if($moneda=="EUR"){
                                $xcadena.= " EUROS $xdecimales/100 EUR ";
                            }
                            
                        }
                        break;
                } // endswitch ($xz)
            } // ENDIF (trim($xaux) != "")
            // ------------------      en este caso, para México se usa esta leyenda     ----------------
            $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // elimina el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
            $xcadena = str_replace("  ", " ", $xcadena); // elimina espacios dobles
            $xcadena = str_replace("UN UN", "UN", $xcadena); // elimina la duplicidad
            $xcadena = str_replace("  ", " ", $xcadena); // elimina espacios dobles
            $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // se corrige la leyenda
            $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // se corrige la leyenda
            $xcadena = str_replace("DE UN", "UN", $xcadena); // se corrige la leyenda
        } // ENDFOR ($xz)
        return trim($xcadena);
    }
    // END FUNCTION

    public function subfijo($xx)
    { // esta función regresa un subfijo para la cifra
        $xx = trim($xx);
        $xstrlen = strlen($xx);
        if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
            $xsub = "";
        //
        if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
            $xsub = "MIL";
        //
        return $xsub;
    }

    //quita los signos, no se necesitan en CFDI SAT
    public function quita_signos($string)
    {
    
        $string = trim($string);
    
        $string = str_replace(
            array(',', '$', '€', '+', '-', '*'),
            array( '',  '',  '',  '',  '',  ''),
            $string
        );
        
        return $string;
    }
    // END FUNCTION

}


/*
*	Prueba de Numeros_a_Letras class
*   
*
*/

//Valores de prueba
$numero = 12345.15;
$letras = "";
$moneda = "MXN";

$Num_a_Letras = new numeros_a_leras();

//envia la informacion para serv procesada
$letras = $Num_a_Letras->numtoletras($numero, $moneda); 

//imprime la informacion
echo "Resultado de letras: ***".$letras."***";


?>