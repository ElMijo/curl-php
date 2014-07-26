<?php
namespace CurlPhp;
/**
* Clase que se encarga de manipular las cabeceras de un objeto curl
*/
class CurlPhpCabeceras
{
    /**
     * Permite extraer las cabeceras usadas en el request
     * @return array                         Arreglo unidimencional de cabeceras
     */
    protected function extraerCabecerasRequest(){

   		$cabeceras =  preg_split(
   			'/\r\n/',
   			curl_getinfo($this->curl, CURLINFO_HEADER_OUT), 
   			NULL, 
   			PREG_SPLIT_NO_EMPTY
   		);

   		return  $this->ordenarCabeceras($cabeceras);
    }

    /**
     * Permite extraer las cabeceras usadas en el response
     * @return array                         Arreglo unidimencional de cabeceras
     */
    protected function extraerCabecerasResponse($cabeceras){

   		$cabeceras =  preg_split(
   			'/\r\n/',
   			$cabeceras, 
   			NULL, 
   			PREG_SPLIT_NO_EMPTY
   		);

   		return  $this->ordenarCabeceras($cabeceras,FALSE);
    }    
	
	/**
     * Permite convertir un areglo de cabeceras en un array asociativo
     * @param  array       $cabeceras     Arreglo unidimencional de cabeceras
     * @param  boolean     $esRequest     Bandera que nos indica si las cabeceras a ordenar no del Request o del Response
     * @return array                      Arreglo asociativo y ordenado de cabeceras
     */
    private function ordenarCabeceras($cabeceras = array(),$esRequest = TRUE){

    	$http_cabeceras = array();

		foreach ($cabeceras as $cabecera) {

			@list($key,$value) = explode(':', $cabecera,2);

			$key              = trim($key);

            $value            = trim($value);

            if (isset($http_cabeceras[$key])) {

                $http_cabeceras[$key] .= ",$value";

            } elseif($value=='') {

                $http_cabeceras[] = $key;

            }
            else{
            	$http_cabeceras[$key] = $value;
            }
		}

		if(isset($cabeceras['0'])){

			$index                  = $esRequest?'Request-Line':'Status-Line';

			$http_cabeceras[$index] = array_shift($http_cabeceras);
		}

		return $http_cabeceras;
    }

        /**
     * Permite convertir el array paso por el usuario en un array de HTTP Headers
     * @param  array      $cabeceras     Arreglo asociativo de cabeceras
     * @return array                     Arreglo de cabeceras entendibles para CuRL
     */
    protected function preparaCabeceras($cabeceras){

    	$this->headers = array_merge($this->headers,$cabeceras);

    	$keys          = array_keys($this->headers);

    	$values        = array_values($this->headers);

    	return array_map(function($key,$value){return "$key : $value";},$keys,$values);
    }
}
?>