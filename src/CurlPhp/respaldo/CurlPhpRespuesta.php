<?php
namespace CurlPhp;
/**
* Clase para generar la respuesta de la petición CuRL
*/
class CurlPhpRespuesta
{
	
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

	/**
	 * Arreglo con todoa los erroes
	 * @var array
	 */
	public $errores            = array();

	function __construct($request,$response,$cuerpo,$errores)
	{
		$this->cabeceras_request  = $request;
		$this->cabeceras_response = $response;
		$this->cuerpo_response    = $cuerpo;
		$this->errores            = $errores;
	}
}
?>