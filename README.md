## Conbierte números a letras

Esta clase convierte de números a letras en formato Mexicano.

<hl>
 <p align="center">
    <h2>SAT Anexo 20 - Versiónes impresas</h2>
 </p> 
</hl>

Basados en el Anexo20 (del diario oficial) del SAT conforme con la especificación ISO 4217 para el uso de monedas.
 
http://omawww.sat.gob.mx/tramitesyservicios/Paginas/documentos/GuiaAnexo20.pdf

<pre> 
Ejemplo: MXN, EUR, USD ...
</pre>

## LÍMITES:

   18 dígitos con 2 decimales
   <pre>
    Ejemplo: 999,999,999,999,999,999.99
    </pre>
    
## DECIMANLES:
 Al final del texto agregará la representacion de decimales como se usa en Mexico
 <pre>
       99/100 M.N.
 </pre>
 
<hl>
  <p align="center">
      <h2>Cómo usar Numeros_a_Letras</h2>
  </p> 
</hl>
  
<p>
  Envía el número y la moneda con la que se quiere trabajar e imprimir
</p>  


  **Fácil !!!**
  


<pre>

/*
*	Prueba clase de Numeros_a_Letras 
*   
*
*/

//Valores de prueba
$numero = 12345.15;
$letras = "";
$moneda = "MXN";  

$Num_a_Letras = new numeros_a_leras();

//envia la informacion para ser procesada
$letras = $Num_a_Letras->numtoletras($numero, $moneda); 

//imprime la informacion
echo "Resultado de letras: ***".$letras."***";

</pre>
  
  <p>
   
  </p>
  
  
  ## GNU GENERAL PUBLIC LICENSE
  <p> </p>
  The GNU General Public License is a free, copyleft license for software and other kinds of works.
  <p> </p>
  <p> </p>
  
  Created by: 	**Hector Manuel Alonso Ortiz**
  <p></p>
  
  eMail: 		**[alonso.hector@gmail.com](mailto:alonso.hector@gmail.com)**
  <p></p>
  
  

