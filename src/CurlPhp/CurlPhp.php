<?php

namespace CurlPhp;

use CurlPhp\Core\CurlPhpBase;


/**
* Clase que permite  el consumo de los servicios cURL
*/
class CurlPhp extends CurlPhpBase
{
		
	/**
	* Arreglo con los metodos que usan query-uri en su url
	* @var array
	*/
	private $query_url_type = array('get','delete','head','options');




	/**
	* Permite ejecutar la solicitud
	* @param String $url Cadena con la url de la conexión
	* @param Array $data Arreglo asociativo con los parametros que se desean enviar
	* @param Callable $success Funcion que debe ejecutarse en caso de que la petición sea exitosa
	* @param Callable $error Funcion que debe ejecutarse en caso de error en la petición
	* @param Callable $complete Funcion que debe ejecutarse cuando la petición culmina sin importar si fue exitosa o no
	* @return mixto El valor que se desea devolver desdela funcion success o error segun sea el caso
	*/
	private function ejecutar($tipo,$url,$data = array(),$success = NULL,$error = NULL,$complete = NULL)
	{

		$this->defineConexion('GET',$url, $data);

		if(!!in_array($tipo, $this->query_url_type)){

			$this->definirOpcionCuRL(CURLOPT_HTTPGET, true);	
		}

		//get
		
		
		

		//post

		if (is_array($data) && empty($data)) {
			$this->eliminarCabecera('Content-Length');
		}

		
		
		//put

		if(is_null($this->optenerOpcionCuRL(CURLOPT_INFILE))&&is_null($this->optenerOpcionCuRL(CURLOPT_INFILE))){
			$this->definirCabeceras('Content-Length', strlen(http_build_query($data)));
		}

		
		

		//patch

		$this->eliminarCabecera('Content-Length');

				

		//delete

		$this->eliminarCabecera('Content-Length');

				


		//head

		$this->definirOpcionCuRL(CURLOPT_NOBODY, true);

				


		//options


		$this->eliminarCabecera('Content-Length');

				



		return $this->ejecutar($success,$error,$complete);

	}


	/**
	* Permite realizar una conexion con el metodo GET
	* @param String $url Cadena con la url de la conexión
	* @param Array $data Arreglo asociativo con los parametros que se desean enviar
* @param Callable $success Funcion que debe ejecutarse en caso de que la petición sea exitosa
* @param Callable $error Funcion que debe ejecutarse en caso de error en la petición
* @param Callable $complete Funcion que debe ejecutarse cuando la petición culmina sin importar si fue exitosa o no
* @return mixto El valor que se desea devolver desdela funcion success o error segun sea el caso
*/
public function get($url,$data = array(),$success = NULL,$error = NULL,$complete = NULL){

$this->defineConexion('GET',$url, $data);
$this->definirOpcionCuRL(CURLOPT_HTTPGET, true);
return $this->ejecutar($success,$error,$complete);
}

/**
* Permite realizar una conexion con el metodo POST
* @param String $url Cadena con la url de la conexión
* @param Array $data Arreglo asociativo con los parametros que se desean enviar
* @param Callable $success Funcion que debe ejecutarse en caso de que la petición sea exitosa
* @param Callable $error Funcion que debe ejecutarse en caso de error en la petición
* @param Callable $complete Funcion que debe ejecutarse cuando la petición culmina sin importar si fue exitosa o no
* @return mixto El valor que se desea devolver desdela funcion success o error segun sea el caso
*/
public function post($url, $data = array(),$success = NULL,$error = NULL,$complete = NULL)
{
if (is_array($data) && empty($data)) {
$this->eliminarCabecera('Content-Length');
}

$this->defineConexion('POST',$url, $data);

return $this->ejecutar($success,$error,$complete);
}

/**
* Permite realizar una conexion con el metodo PUT
* @param String $url Cadena con la url de la conexión
* @param Array $data Arreglo asociativo con los parametros que se desean enviar
* @param Callable $success Funcion que debe ejecutarse en caso de que la petición sea exitosa
* @param Callable $error Funcion que debe ejecutarse en caso de error en la petición
* @param Callable $complete Funcion que debe ejecutarse cuando la petición culmina sin importar si fue exitosa o no
* @return mixto El valor que se desea devolver desdela funcion success o error segun sea el caso
*/
public function put($url, $data = array(),$success = NULL,$error = NULL,$complete = NULL)
{

if(is_null($this->optenerOpcionCuRL(CURLOPT_INFILE))&&is_null($this->optenerOpcionCuRL(CURLOPT_INFILE))){
$this->definirCabeceras('Content-Length', strlen(http_build_query($data)));
}

$this->defineConexion('PUT',$url, $data);

return $this->ejecutar($success,$error,$complete);
}

/**
* Permite realizar una conexion con el metodo PATCH
* @param String $url Cadena con la url de la conexión
* @param Array $data Arreglo asociativo con los parametros que se desean enviar
* @param Callable $success Funcion que debe ejecutarse en caso de que la petición sea exitosa
* @param Callable $error Funcion que debe ejecutarse en caso de error en la petición
* @param Callable $complete Funcion que debe ejecutarse cuando la petición culmina sin importar si fue exitosa o no
* @return mixto El valor que se desea devolver desdela funcion success o error segun sea el caso
*/
public function patch($url, $data = array(),$success = NULL,$error = NULL,$complete = NULL)
{
$this->eliminarCabecera('Content-Length');

$this->defineConexion('PATCH',$url, $data);
return $this->ejecutar($success,$error,$complete);
}

/**
* Permite realizar una conexion con el metodo DELETE
* @param String $url Cadena con la url de la conexión
* @param Array $data Arreglo asociativo con los parametros que se desean enviar
* @param Callable $success Funcion que debe ejecutarse en caso de que la petición sea exitosa
* @param Callable $error Funcion que debe ejecutarse en caso de error en la petición
* @param Callable $complete Funcion que debe ejecutarse cuando la petición culmina sin importar si fue exitosa o no
* @return mixto El valor que se desea devolver desdela funcion success o error segun sea el caso
*/
public function delete($url, $data = array(),$success = NULL,$error = NULL,$complete = NULL)
{

$this->eliminarCabecera('Content-Length');

$this->defineConexion('DELETE',$url, $data);
return $this->ejecutar($success,$error,$complete);

}

/**
* Permite realizar una conexion con el metodo HEAD
* @param String $url Cadena con la url de la conexión
* @param Array $data Arreglo asociativo con los parametros que se desean enviar
* @param Callable $success Funcion que debe ejecutarse en caso de que la petición sea exitosa
* @param Callable $error Funcion que debe ejecutarse en caso de error en la petición
* @param Callable $complete Funcion que debe ejecutarse cuando la petición culmina sin importar si fue exitosa o no
* @return mixto El valor que se desea devolver desdela funcion success o error segun sea el caso
*/
public function head($url, $data = array(),$success = NULL,$error = NULL,$complete = NULL)
{
$this->definirOpcionCuRL(CURLOPT_NOBODY, true);

$this->defineConexion('HEAD',$url, $data);
return $this->ejecutar($success,$error,$complete);

}

/**
* Permite realizar una conexion con el metodo OPTIONS
* @param String $url Cadena con la url de la conexión
* @param Array $data Arreglo asociativo con los parametros que se desean enviar
* @param Callable $success Funcion que debe ejecutarse en caso de que la petición sea exitosa
* @param Callable $error Funcion que debe ejecutarse en caso de error en la petición
* @param Callable $complete Funcion que debe ejecutarse cuando la petición culmina sin importar si fue exitosa o no
* @return mixto El valor que se desea devolver desdela funcion success o error segun sea el caso
*/
public function options($url, $data = array())
{

$this->eliminarCabecera('Content-Length');

$this->defineConexion('DELETE',$url, $data);
return $this->ejecutar($success,$error,$complete);

}
	private function defineConexion($tipo,$url,$data=array()){

		$data = $this->preparaData($data);

		$this->definirOpcionCuRL(CURLOPT_URL, $this->prepararURL($url,$data));

		$this->definirOpcionCuRL(CURLOPT_CUSTOMREQUEST,$tipo);

		if(!in_array($tipo, $this->query_url_type)){

			$this->definirOpcionCuRL(CURLOPT_POST, true);

			$this->definirOpcionCuRL(CURLOPT_POSTFIELDS, $data);
		}
	}

	private function prepararURL($url, $data)
	{

		return $url.(is_array($data)?'':$data);

		/*$query_data = in_array($tipo, $this->query_url_type)&&!empty($data)?'?'.http_build_query($data):'';

		return "$url$query_data";*/
	}

	/**
	 * Permite preparar la data para la solicitud
	 * @param  array            $data      Arreglo de parametros
	 * @return array|string                Arreglo de parametros o url query
	 */
	private function preparaData($data)
	{

		$datos_binarios = 0;

		foreach ($data as $key => $value) {

			$data[$key] = is_array($value)&&empty($value)?'':$value;

			if($this->esArchivo($value)){

				$filename = preg_replace('/^@/', '', $value);

				$data[$key] = !!class_exists('CURLFile')?new \CURLFile($filename):"@$filename";

				$datos_binarios+=1;

			}

		}

		return $datos_binarios==0?'?'.http_build_query($data):$data;
	}

	/**
	 * Permite validar la existencia fisica de un parametro tipo filename
	 * @param  string     $valor      Cadena de texto a evaluar
	 * @return boolean                Si es un archivo valido devuelve TRUE o FALSe en caso contrario
	 */
	private function esArchivo($valor = '')
	{

		return !!file_exists(preg_replace('/^@/', '', $valor));

	}
}
?>