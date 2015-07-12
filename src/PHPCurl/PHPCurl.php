<?php

namespace PHPTools\PHPCurl;

use CurlPhp\Core\CurlPhpBase;


/**
* Clase que permite  el consumo de los servicios cURL
*/
class PHPCurl extends CurlPhpBase
{

	/**
	* Arreglo con los metodos que usan query-uri en su url
	* @var array
	*/
	private $query_url_type    = array('GET','DELETE','HEAD','OPTIONS');

	/**
	* Arreglo con los metodos que no necesitan content-length
	* @var array
	*/
	private $no_content_length = array('PATCH','DELETE','OPTIONS','HEAD');


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

		$this->defineConexion($tipo,$url, $data);

		if(!!in_array($tipo, $this->query_url_type))
		{

			$this->definirOpcionCuRL(CURLOPT_HTTPGET, true);
		}

		if (empty($data)||in_array($tipo, $this->no_content_length))
		{

			$this->eliminarCabecera('Content-Length');

		}

		if($tipo == 'HEAD')
		{

			$this->definirOpcionCuRL(CURLOPT_NOBODY, true);

		}

		$resultado = $this->ejecurtarCurl();

		if(!!$resultado->errores->error){

			$ejecución = $this->ejecutarFuncion($error, $resultado->errores);

		}
		else{

			$ejecución = $this->ejecutarFuncion($success, $resultado->respuesta);

		}

       	$this->ejecutarFuncion($complete, $respuesta,$resultado->errores);

        return $ejecución;

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
	public function get($url,$data = array(),$success = NULL,$error = NULL,$complete = NULL)
	{

		return $this->ejecutar('GET',$url,$data,$success,$error,$complete);
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

		return $this->ejecutar('POST',$url,$data,$success,$error,$complete);

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

		return $this->ejecutar('PUT',$url,$data,$success,$error,$complete);

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

		return $this->ejecutar('PATCH',$url,$data,$success,$error,$complete);

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

		return $this->ejecutar('DELETE',$url,$data,$success,$error,$complete);

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

		return $this->ejecutar('HEAD',$url,$data,$success,$error,$complete);

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

		return $this->ejecutar('OPTIONS',$url,$data,$success,$error,$complete);

	}

	/**
	 * Permite definir los parametyros de la conexión
	 * @param  string     $tipo     Cadena con el nombre del metodo de conexión
	 * @param  string     $url      Cadena con la url de conexión
	 * @param  array      $data     Arreglo asociativo con los parametros quer se desean enviar
	 * @return void
	 */
	private function defineConexion($tipo,$url,$data=array()){

		if(!!in_array($tipo, $this->query_url_type))
		{

			$data = $this->preparaData($data);

		}

		$this->definirOpcionCuRL(CURLOPT_URL, $this->prepararURL($url,$data));

		$this->definirOpcionCuRL(CURLOPT_CUSTOMREQUEST,$tipo);

		if(!in_array($tipo, $this->query_url_type)){

			$this->definirOpcionCuRL(CURLOPT_POST, true);

			$this->definirOpcionCuRL(CURLOPT_POSTFIELDS, $data);
		}
	}

	/**
	 * Permite preparar la url segun el metodo de la solicitud
	 * @param  string     $url      Cadena con la url de la solicitud
	 * @param  array      $data     Arreglo asociativo con los parametros a enviar
	 * @return string               Cadena con la url de la solicitud
	 */
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

		$query = '?'.http_build_query($data);

		$query = $query=='?'?'':$query;

		return $datos_binarios==0?$query:$data;
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
}
?>