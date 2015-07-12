<?php
/**
 * Este es el archivo que sera importado por el usuario
 * 
 * @author Jerry Anselmi <jerry.anselmi@gmail.com>
 * @license http://es.wikipedia.org/wiki/Licencia_MIT MIT
 * @copyright 2015 Jerry Anselmi.
 */

namespace PHPTools\PHPCurlClient\Core;

/**
* Clase que se encarga de manipular las cabeceras de un objeto cURL
*/
class PHPCurlClientHeader
{
    /**
    * Cabeceras de la peticion
    * @var array
    */
    protected $request_headers = array();
    
    /**
     * Cabeceras de la Respuesta
     * @var array
     */
    protected $response_headers = array();

    /**
     * Permite convertir el array paso por el usuario en un array de HTTP Headers
     * @param  array      $headers     Arreglo asociativo de cabeceras
     * @return array                     Arreglo de cabeceras entendibles para CuRL
     */
    protected function parseHeaders($headers)
    {
        $this->headers = array_merge($this->headers,$headers);

        $keys = array_keys($this->headers);

        $values = array_values($this->headers);

        return array_map(function($key,$value){return "$key : $value";},$keys,$values);
    }

    protected function extraerCabeceras($headers)
    {

        $this->request_headers = $this->extractRequestHeaders();

        if(preg_match('/^HTTP/',$headers))
        {
            $this->response_headers = $this->extractResponseHeaders($headers);
        }
    }

    /**
     * Permite extraer las cabeceras usadas en el request
     * @return array                         Arreglo unidimencional de cabeceras
     */
    private function extractRequestHeaders()
    {
        $headers =  preg_split(
            '/\r\n/',
            curl_getinfo($this->curl, CURLINFO_HEADER_OUT), 
            NULL, 
            PREG_SPLIT_NO_EMPTY
        );
        return  $this->parserExtractHeaders($headers);
    }

    /**
     * Permite extraer las cabeceras usadas en el response
     * @return array                         Arreglo unidimencional de cabeceras
     */
    private function extractResponseHeaders($headers)
    {
        $headers =  preg_split(
            '/\r\n/',
            $headers, 
            NULL, 
            PREG_SPLIT_NO_EMPTY
        );
        return  $this->parserExtractHeaders($headers,FALSE);
    }

    /**
     * Permite convertir un areglo de cabeceras en un array asociativo
     * @param  array       $headers     Arreglo unidimencional de cabeceras
     * @param  boolean     $isRequest     Bandera que nos indica si las cabeceras a ordenar no del Request o del Response
     * @return array                      Arreglo asociativo y ordenado de cabeceras
     */
    private function parserExtractHeaders($headers = array(),$isRequest = TRUE)
    {

        $http_headers = array();

        foreach ($headers as $header)
        {
            @list($key,$value) = explode(':', $header,2);

            $key = trim($key);

            $value = trim($value);

            if (isset($http_headers[$key]))
            {
                $http_headers[$key] .= ",$value";

            }
            elseif($value=='')
            {
                $http_headers[] = $key;
            }
            else
            {
                $http_headers[$key] = $value;
            }
        }

        if(isset($headers['0']))
        {
            $index = $isRequest?'Request-Line':'Status-Line';

            $http_headers[$index] = array_shift($http_headers);
        }

        return $http_headers;
    } 
}