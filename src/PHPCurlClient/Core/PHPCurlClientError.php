<?php
/**
 * Este es el archivo contendra os mensajes que se utilizaran para comunicarse con el usuario.
 * 
 * @author Jerry Anselmi <jerry.anselmi@gmail.com>
 * @license http://es.wikipedia.org/wiki/Licencia_MIT MIT
 * @copyright 2015 Jerry Anselmi.
 */

namespace PHPTools\PHPCurlClient\Core;

/**
* Clase que contiene los metodoa base de la conexión cURL
*/
class PHPCurlClientError
{
    /**
     * Codigo del error obtenido
     * @var integer
     */
    public $code = 0;

    /**
     * Tipo de error obtenido cURL o HTTP
     * @var string
     */
    public $type;

    /**
     * Mensaje de error obtenido
     * @var string
     */
    public $message;

    function __construct($curl,$headers)
    {
        $curl_error_code = curl_errno($curl);

        $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if($curl_error_code > 0)
        {
            $this->type = 'cURL';

            $this->code = $curl_error_code;

            $this->message = curl_error($curl);
        }
        else if(!!in_array(floor($http_status_code / 100), array(4, 5)))
        {
            $this->type = 'HTTP';

            $this->code = $http_status_code;

            if (isset($headers['Status-Line']))
            {
                $this->message = $headers['Status-Line'];
            }
        }
    }
}