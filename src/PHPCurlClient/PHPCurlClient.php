<?php
/**
 * Este es el archivo que sera importado por el usuario
 * 
 * @author Jerry Anselmi <jerry.anselmi@gmail.com>
 * @license http://es.wikipedia.org/wiki/Licencia_MIT MIT
 * @copyright 2015 Jerry Anselmi.
 */

namespace PHPTools\PHPCurlClient;

/**
* Clase cliente cURL.
*/
class PHPCurlClient extends PHPTools\PHPCurlClient\Request\PHPCurlClientRequest
{
    /**
     * Metodo que permite realizar n petición de tipo GET
     * @param  string $url    Cadena de texto con formato url que va ha ser utilizado para realizar la petición.
     * @param  array  $params Lista asociaativa de los parametros que van aa ser enviados en la petición.
     * @return \PHPTools\PHPCurlClient\Response\PHPCurlClientResponse.
     */
    final public function get($url,$params = array())
    {
        return $this->setMethod('GET')->setUrl($url)->send($params);
    }

    /**
     * Metodo que permite realizar n petición de tipo POST
     * @param  string $url    Cadena de texto con formato url que va ha ser utilizado para realizar la petición.
     * @param  array  $params Lista asociaativa de los parametros que van aa ser enviados en la petición.
     * @return \PHPTools\PHPCurlClient\Response\PHPCurlClientResponse.
     */
    final public function post($url,$params = array())
    {
        return $this->setMethod('POST')->setUrl($url)->send($params);
    }

    /**
     * Metodo que permite realizar n petición de tipo PUT
     * @param  string $url    Cadena de texto con formato url que va ha ser utilizado para realizar la petición.
     * @param  array  $params Lista asociaativa de los parametros que van aa ser enviados en la petición.
     * @return \PHPTools\PHPCurlClient\Response\PHPCurlClientResponse.
     */
    final public function put($url,$params = array())
    {
        return $this->setMethod('PUT')->setUrl($url)->send($params);
    }

    /**
     * Metodo que permite realizar n petición de tipo DELETE
     * @param  string $url    Cadena de texto con formato url que va ha ser utilizado para realizar la petición.
     * @param  array  $params Lista asociaativa de los parametros que van aa ser enviados en la petición.
     * @return \PHPTools\PHPCurlClient\Response\PHPCurlClientResponse.
     */
    final public function delete($url,$params = array())
    {
        return $this->setMethod('DELETE')->setUrl($url)->send($params);
    }

    /**
     * Metodo que permite realizar n petición de tipo HEAD
     * @param  string $url    Cadena de texto con formato url que va ha ser utilizado para realizar la petición.
     * @param  array  $params Lista asociaativa de los parametros que van aa ser enviados en la petición.
     * @return \PHPTools\PHPCurlClient\Response\PHPCurlClientResponse.
     */
    final public function head($url,$params = array())
    {
        return $this->setMethod('HEAD')->setUrl($url)->send($params);
    }

    /**
     * Metodo que permite realizar n petición de tipo OPTIONS
     * @param  string $url    Cadena de texto con formato url que va ha ser utilizado para realizar la petición.
     * @param  array  $params Lista asociaativa de los parametros que van aa ser enviados en la petición.
     * @return \PHPTools\PHPCurlClient\Response\PHPCurlClientResponse.
     */
    final public function options($url,$params = array())
    {
        return $this->setMethod('OPTIONS')->setUrl($url)->send($params);
    }

    /**
     * Metodo que permite realizar n petición de tipo TRACE
     * @param  string $url    Cadena de texto con formato url que va ha ser utilizado para realizar la petición.
     * @param  array  $params Lista asociaativa de los parametros que van aa ser enviados en la petición.
     * @return \PHPTools\PHPCurlClient\Response\PHPCurlClientResponse.
     */
    final public function trace($url,$params = array())
    {
        return $this->setMethod('TRACE')->setUrl($url)->send($params);
    }
}