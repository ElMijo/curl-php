<?php
namespace PHPTools\PHPCurl\Core;
/**
* Clase que se encarga de manipular las cabeceras de un objeto curl
*/
class PHPCurlCabeceras
{

    /**
  * Cabeceras de la peticion
  * @var array
  */
  protected $cabeceras_solicitud  = array();

  /**
   * Cabeceras de la Respuesta
   * @var array
   */
  protected $cabeceras_respuesta = array();


  protected function extraerCabeceras($cabeceras){

    $this->cabeceras_solicitud = $this->extraerCabecerasRequest();

    if(preg_match('/^HTTP/',$cabeceras)){

      $this->cabeceras_respuesta = $this->extraerCabecerasResponse($cabeceras);

    }

  }


  /**
   * Permite extraer las cabeceras usadas en el request
   * @return array                         Arreglo unidimencional de cabeceras
   */
  private function extraerCabecerasRequest(){

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
  private function extraerCabecerasResponse($cabeceras){

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

    	$this->cabeceras = array_merge($this->cabeceras,$cabeceras);

    	$keys          = array_keys($this->cabeceras);

    	$values        = array_values($this->cabeceras);

    	return array_map(function($key,$value){return "$key : $value";},$keys,$values);
    }
}
?>