<?php
/**
 * Este es el archivo contiene la clase que permitira construir un objeto cURL Request.
 * 
 * @author Jerry Anselmi <jerry.anselmi@gmail.com>
 * @license http://es.wikipedia.org/wiki/Licencia_MIT MIT
 * @copyright 2015 Jerry Anselmi.
 */

namespace PHPTools\PHPCurlClient\Request;

/**
* Clase que permite crear un objeto cURL Request cURL
*/
class PHPCurlClientRequest extends \PHPTools\PHPCurlClient\Core\PHPCurlClientFactory
{
    /**
    * Arreglo con los metodos que usan query-uri en su url
    * @var array
    */
    private $query_url_type = array('GET','DELETE','HEAD','OPTIONS');

    /**
    * Arreglo con los metodos que no necesitan content-length
    * @var array
    */
    private $no_content_length = array('PATCH','DELETE','OPTIONS','HEAD');

    /**
     * Lista de metodos disponibles paraa realizar una petición.
     * @var array
     */
    private $http_methods = array('OPTIONS', 'GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'TRACE');

    /**
     * Valor que representa la url de la petición
     * @var string
     */
    private $url;

    /**
     * Valor que representa los parametros de laa petición
     * @var array
     */
    private $params;

    /**
     * Valor que representa el metodo de la petición
     * @var string
     */
    private $method;

    /**
     * Valor que representa el query de la petición
     * @var string
     */
    private $query;    

    /**
     * Metodo que permite definir el tipo de petición.
     * @param string $method Cadena de texto con el nombre del tipo de petición.
     * @return self
     */
    protected function setMethod($method)
    {
        $method = strtoupper($method);

        $this->method = in_array($method,$this->http_methods)?$method:'GET';

        return $this;
    }

    /**
     * Metodo que permite definir la url de la petición.
     * @param url $url Cadena de texto con formato url.
     * @return self
     */
    protected function setUrl($url)
    {
        $this->url = filter_var($url,FILTER_VALIDATE_URL)?$url:null;

        return $this;
    }

    /**
     * Metodo que permite ejecutar la petición.
     * @param  array  $params Lista asociativa de parametros que se desea enviar en la petición.
     * @return \PHPTools\PHPCurlClient\Response\PHPCurlClientResponse
     */
    protected function send($params = array())
    {
        $this->params = $this->is_array_assoc($params)?$params:array();

        return $this->buildParams()->buildUrl()->exec();
    }

    /**
     * Metodo quue permite construir el query que va a ser enviado al¿¿en la petición.
     * @return self
     */
    private function buildParams()
    {
        $this->query = http_build_query($this->params);
        return $this;
    }

    /**
     * Metod que permite construir La url de la petición y definir los parametros finales de la petición.
     * @return self
     */
    private function buildUrl()
    {
        if (in_array($this->method,$this->$query_url_type))
        {
            $url = parse_url($this->url);
            $url['query'] = $this->query;
            $this->url = http_build_url($url);
        }
        else
        {
            $this->setOpt(CURLOPT_POST, true);
            $this->setOpt(CURLOPT_POSTFIELDS,$this->query);
        }

        if (empty($this->query)||in_array($this->method, $this->no_content_length))
        {
            $this->removeHeader('Content-Length');
        }

        if($this->method == 'HEAD')
        {
            $this->setOpt(CURLOPT_NOBODY, true);
        }

        $this->setOpt(CURLOPT_CUSTOMREQUEST,$this->method);

        $this->setOpt(CURLOPT_URL, $this->url);

        return $this; 
    }


    // /**
    //  * Permite preparar la data para la solicitud
    //  * @param  array            $data      Arreglo de parametros
    //  * @return array|string                Arreglo de parametros o url query
    //  */
    // private function preparaData($data)
    // {

    //  $datos_binarios = 0;

    //  foreach ($data as $key => $value) {

    //      $data[$key] = is_array($value)&&empty($value)?'':$value;

    //      if($this->esArchivo($value)){

    //          $filename = preg_replace('/^@/', '', $value);

    //          $data[$key] = !!class_exists('CURLFile')?new \CURLFile($filename):"@$filename";

    //          $datos_binarios+=1;

    //      }

    //  }

    //  $query = '?'.http_build_query($data);

    //  $query = $query=='?'?'':$query;

    //  return $datos_binarios==0?$query:$data;
    // }

    // /**
    //  * Permite validar la existencia fisica de un parametro tipo filename
    //  * @param  string     $valor      Cadena de texto a evaluar
    //  * @return boolean                Si es un archivo valido devuelve TRUE o FALSe en caso contrario
    //  */
    // private function esArchivo($valor = '')
    // {

    //  return !!file_exists(preg_replace('/^@/', '', $valor));

    // }
}