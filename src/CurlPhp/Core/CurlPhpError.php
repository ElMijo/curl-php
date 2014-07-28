<?php

namespace CurlPhp\Core;

/**
* Clase para procesar los posibles errores de la conexión Curl
*/
class CurlPhpError
{
	/**
     * Codigo cURL de error
     * @var string
     */
    private $curl_error_code = '';

    /**
     * Codigo HTTP de la respusta
     * @var string
     */
    private $http_status_code = '';

    /**
     * Tipo de error obtenido cURL o HTTP
     * @var string
     */
    public $error_type = NULL;

    /**
     * Codigo del error obtenido
     * @var integer
     */
    public $error_code = 0;

    /**
     * Mensaje de error obtenido
     * @var string
     */
    public $error_message = '';

    /**
     * Permite saber si se obtuvo error
     * @var boolean
     */
    public $error = TRUE;


	function __construct($curl,$cabeceras)
	{

        $this->curl_error_code = curl_errno($curl);

        $this->http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if($this->hayErrorCurl())
        {

            $this->error_type = 'cURL';

            $this->error_code = $this->curl_error_code;

            $this->error_message = curl_error($curl);

        }
        else if($this->hayErrorHttp())
        {

            $this->error_type = 'HTTP';

            $this->error_code = $this->http_status_code;

            if (isset($cabeceras['Status-Line'])) {

                $this->error_message = $cabeceras['Status-Line'];

            }

        }
        else
        {

            $this->error = FALSE;

        }
	}

    /**
     * Permite saber si hay un error cURL
     * @return boolean             Si Existe error cURL devuelve TRUE o FALSe en caso contrario
     */
    private function hayErrorCurl()
    {

        return !!($this->curl_error_code > 0);

    }

    /**
     * Permite saber si hubo error HTTP
     * @return boolean             Si Existe error HTTP devuelve TRUE o FALSe en caso contrario
     */
    private function hayErrorHttp()
    {

        return !!in_array(floor($this->http_status_code / 100), array(4, 5));

    }
}
?>