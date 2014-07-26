<?php

namespace CurlPhp;


use CurlPhp\CurlPhpCabeceras;
use CurlPhp\CurlPhpRespuesta;

/**
* Clase que permite utilizar la libreria CuRL de forma facil
*/
class CurlPhp extends CurlPhpCabeceras
{


	/**
	 * Patron utilizado para validar los contenidos JSON
	 */
	const PATRON_JSON = '/^application\/json/i';

	/**
	 * Patron utilizado para validar los contenidos atom/xml
	 */
	const PATRON_ATOM_XML = '/^application\/atom\+xml/i';
	/**
	 * Patron utilizado para validar los contenidos rss/xml
	 */
	const PATRON_RSS_XML  = '/^application\/rss\+xml/i';
	/**
	 * Patron utilizado para validar los contenidos aplication/xml
	 */
	const PATRON_APP_XML  = '/^application\/xml/i';
	/**
	 * Patron utilizado para validar los contenidos text/xml
	 */
	const PATRON_TEXT_XML = '/^text\/xml/i';







    /**
     * Error Curl
     * @var [type]
     */
    private $curl_error         = NULL;

    /**
     * Codigo de Error CuRL
     * @var [type]
     */
    private $curl_error_code    = NULL;

    /**
     * Mensaje de Error CuRL
     * @var [type]
     */
    private $curl_error_message = NULL;



    /**
     * Error HTTP
     * @var [type]
     */
    private $http_error         = NULL;

	/**
	 * Codigo HTTP del status de la respuesta
	 * @var [type]
	 */
	private $http_status_code   = NULL;

    /**
     * HTTP mensaje de error
     * @var [type]
     */
    private $http_error_message = NULL;


	/**
	 * Error
	 * @var [type]
	 */
	private $error              = NULL;

	/**
	 * Codigo de Error
	 * @var [type]
	 */
	private $error_code         = NULL;



	

    






    public function ejecutar($success,$error,$complete){

    	$this->curl_respuesta    = curl_exec($this->curl);

    	$this->carturarErrores();

		$this->procesarRespuesta();

		if ($this->error) {

            if (isset($this->cabeceras_response['Status-Line'])) {

                $this->http_error_message = $this->cabeceras_response['Status-Line'];

            }

        }

        $this->error_message = $this->curl_error ? $this->curl_error_message : $this->http_error_message;

        
        $respuesta           = new CuRLPhpRespuesta(
        							$this->cabeceras_request,
        							$this->cabeceras_response,
        							$this->cuerpo_response,
        							$this->obtenerErrores()
        );

        $ejecución = $this->ejecutarFuncion(!!$this->error?$error:$success, $respuesta);

       	$this->ejecutarFuncion($complete, $respuesta);

        return $ejecución;
    }

    /**
     * Permite ejecutar una funcion,los parametros deben ser pasados inmediatamente despues de la funcion a ejecutar
     * @param  callable     $funcion     Funcion que se desea ejecutar
     */
    private function ejecutarFuncion($funcion)
    {
        if (is_callable($funcion)) {
            $args = func_get_args();
            array_shift($args);
            return call_user_func_array($funcion, $args);
        }
        return NULL;
    }

    /**
     * Permite procesar la respuesta de la peticion y separar las cabeceras del curpo de la respuesta
     */
    private function procesarRespuesta(){

    	$this->cabeceras_request = $this->extraerCabecerasRequest();

        if (!(strpos($this->curl_respuesta, "\r\n\r\n") === false)) {

        	list($cabeceras,$cuerpo) = explode("\r\n\r\n", trim($this->curl_respuesta));

        	if(preg_match('/^HTTP/',$cabeceras)){

        		$this->cabeceras_response = $this->extraerCabecerasResponse($cabeceras);
        	}

        	if(isset($this->cabeceras_response['Content-Type'])){

        		$tipo_contenido = $this->cabeceras_response['Content-Type'];

        		if($this->esJSON($tipo_contenido)){

        			$cuerpo = json_decode($cuerpo, false);

        		}elseif ($this->esXML($tipo_contenido)){

        			$cuerpo = @simplexml_load_string($cuerpo);

        		}
        	}

        	$this->cuerpo_response = $cuerpo;
        }
    }

    /**
     * Permite saber si un tipo de contenido es JSON
     * @param  String     $tipo_contenido     Cadena con el contenido a evaluar
     * @return Boolean                        Devuelve TRUE si el Contenido es JSON o FALSE en caso contrario
     */
    private function esJSON($tipo_contenido){

    	 return preg_match(self::PATRON_JSON, $tipo_contenido);

    }
    /**
     * Permite saber si un tipo de contenido es XML
     * @param  String     $tipo_contenido     Cadena con el contenido a evaluar
     * @return Boolean                        Devuelve TRUE si el Contenido es XML o FALSE en caso contrario
     */

    private function esXML($tipo_contenido){

    	return !!(preg_match(self::PATRON_ATOM_XML, $tipo_contenido)||
			preg_match(self::PATRON_RSS_XML, $tipo_contenido)||
			preg_match(self::PATRON_APP_XML, $tipo_contenido)||
			preg_match(self::PATRON_TEXT_XML, $tipo_contenido)
		);
    }


    /**
     * Permite Obtener Todos los Errores
     * @return array
     */
    public function obtenerErrores(){
    	return array(
        	"error"              => $this->error,
        	"error_code"         => $this->error_code,
        	"http_error"         => $this->http_error,
        	"http_status_code"   => $this->http_status_code,
        	"http_error_message" => $this->http_error_message,
        	"curl_error"         => $this->curl_error,
    		"curl_error_code"    => $this->curl_error_code,
    		"curl_error_message" => $this->curl_error_message
    	);
    }





    /**
     * Permite capturar todos las definiciones de errores
     * @return [type] [description]
     */
    private function carturarErrores(){

    	$this->curl_error_code    = curl_errno($this->curl);
    	$this->curl_error_message = curl_error($this->curl);
        $this->curl_error         = !($this->curl_error_code === 0);
        $this->http_status_code   = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $this->http_error         = in_array(floor($this->http_status_code / 100), array(4, 5));
        $this->error              = $this->curl_error || $this->http_error;
        $this->error_code         = $this->error ? ($this->curl_error ? $this->curl_error_code : $this->http_status_code) : 0;
        $this->http_error_message = '';
        return $this;
    }


    /**
     * Permite validar si un arrays es asociativo
     * @param  Array      $array     Arreglo que se desea evaluar
     * @return boolean               Devuelve TRUE si el arreglo es asociativo o FAÑSE en caso contrario
     */
    private function is_array_assoc($array = array())
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }

}


?>