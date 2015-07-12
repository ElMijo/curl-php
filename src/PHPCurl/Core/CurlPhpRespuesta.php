<?php
namespace PHPTools\PHPCurl\Core;
/**
* Clase para generar la respuesta de la petición CuRL
*/
class PHPCurlRespuesta
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
	 * Cabeceras de la peticion
	 * @var array
	 */
	public $cabeceras_request  = array();

	/**
	 * Cabeceras de la Respuesta
	 * @var array
	 */
	public $cabeceras_response = array();

	/**
	 * Cuerpo de la respuesta de la peticion
	 * @var string
	 */
	public $cuerpo_response    = '';

	function __construct($request,$response,$cuerpo)
	{
		$this->cabeceras_request  = $request;
		$this->cabeceras_response = $response;
		$this->cuerpo_response    = $cuerpo;

		$this->procesarCuerpo();
	}

	/**
     * Permite procesar el cuerpo de la respuesta y separar las cabeceras del curpo de la respuesta
     */
    private function procesarCuerpo(){

    	if(isset($this->cabeceras_response['Content-Type'])){

        	$tipo_contenido = $this->cabeceras_response['Content-Type'];

        	if($this->esJSON($tipo_contenido)){

        		$this->cuerpo_response = json_decode($this->cuerpo_response, false);

        	}elseif ($this->esXML($tipo_contenido)){

       			$this->cuerpo_response = @simplexml_load_string($this->cuerpo_response);
        	}

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

}
?>