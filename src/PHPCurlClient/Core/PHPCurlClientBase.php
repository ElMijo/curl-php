<?php
/**
 * Este es el archivo que sera importado por el usuario
 * 
 * @author Jerry Anselmi <jerry.anselmi@gmail.com>
 * @license http://es.wikipedia.org/wiki/Licencia_MIT MIT
 * @copyright 2015 Jerry Anselmi.
 */

namespace PHPTools\PHPCurlClient\Core;

use PHPTools\PHPCurlClient\Core\PHPCurlClientMessage as MSG;

/**
* Clase que contiene los metodoa base de la conexión cURL
*/
class PHPCurlClientFactory extends \PHPTools\PHPErrorLog\PHPCurlClientHeader
{
    /**
     * Versión de la clase
     */
    const VERSION = '1.0';

    /**
     * Respuesta de la ejecucion del objeto CuRL
     * @var string
     */
    private $raw_response = NULL;

    /**
     * Cuerpo de la respuesta de la peticion
     * @var string
     */
    protected $body_response = '';

    /**
     * Arreglo con los COOKIES que se desean incluir en el Request
     * @var array
     */
    protected $cookies = array();

    /**
     * Arreglo con los cabeceras que se desean incluir en el Request
     * @var array
     */
    protected $headers = array();

    /**
     * Arreglo con las OPCIONES que se desean incluir en el Request
     * @var array
     */
    protected $options = array();

    /**
     * Vaalores baasicos de las cabeceras
     * @var array
     */
    private $default_options = array(
        CURLINFO_HEADER_OUT    => 'CURLINFO_HEADER_OUT',
        CURLOPT_HEADER         => 'CURLOPT_HEADER',
        CURLOPT_RETURNTRANSFER => 'CURLOPT_RETURNTRANSFER'
    );

    /**
     * Objeto CuRL
     * @var CuRL
     */
    private $curl;

    /**
     * Objeto quue manejara los logs.
     * @var \PHPTools\PHPErrorLog\PHPErrorLog
     */
    private $logger;

    function __construct()
    {
        if(!extension_loaded('curl'))
        {
            throw new \ErrorException(MSG::NO_EXTENCION_CURL);
        }

        $this->curl = curl_init();
        $this->logger = new PHPTools\PHPErrorLog\PHPErrorLog();

        $this->setDefaultUserAgent();
        $this->setOpt(CURLINFO_HEADER_OUT, true);
        $this->setOpt(CURLOPT_HEADER, true);
        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
    }

    function __destruct()
    {
        if (is_resource($this->curl))
        {
            curl_close($this->curl);
        }
    }

    /**
     * Permite definir opociones propias de CuRL
     * @param string                     $option     Cadena con el nombre de la opcion que se desea definir
     * @param string|Boolean|Numeric     $value      Valor que se desea definir
     * @param Boolean                                Devuelve TRUE si la operacion se realizo corectamente o FALSE en caso contrario
     */
    final public function setOpt($option, $value)
    {

        $set_opt = False,
        if (in_array($option, array_keys($this->default_options), true) && !($value === true))
        {
            $msg = sprintf(MSG::OPCION_NECESARIA,$this->default_options[$option],PEL_WARNING);

            $this->logger($msg);
        }
        else
        {
            $this->options[$option] = $value;

            $set_opt = curl_setopt($this->curl, $option, $value);
        }
        return $set_opt;
    }

    /**
     * Permite obtener un valor definido entre las opciones CuRL
     * @param  string                          $option     Cadena con el nombre de la opcion que se desea obtener
     * @return string|Boolean|Numeric|NULL     $value      Devuelve el Valor definido o NULL en caso de no estar definido
     */
    final public function getOpt($option)
    {
        return isset($this->opciones[$option])?$this->options[$option]:NULL;
    }

    /**
     * Permite definir la o las cabeceras que se desean incluir en la conexion
     * @param  array     $cabeceras     Arreglo asociativo de Cabeceras
     * @return CurlPhp
     */
    final public function addHeader($headers = array())
    {
        if($this->is_array_assoc($headers))
        {
            $headers = $this->parseHeaders($headers);

            $this->setOpt(CURLOPT_HTTPHEADER, $headers);
        }
        return $this;
    }

    /**
     * Permite eliminar cabeceras de la conexión
     * @param  String     $key     Cadena con el nombre de la cabecera a eliminar
     * @return CurlPhp
     */
    final public function removeHeader($key)
    {
        if(!empty($key))
        {
            unset($this->headers[$key]);

            $this->setOpt(CURLOPT_HTTPHEADER,$this->headers);
        }
        return $this;
    }

    /**
     * Permite definir el metodo de autenticación basica como metodo de autenticacion
     * @param  string     $username     Cadena con el nombre de usuario
     * @param  string     $password       Cadena con la clave
     * @return self
     */
    final public function setBasicAuth($username, $password)
    {
        if(!empty($username)&&!empty($password))
        {
            $this->setOpt(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            $this->setOpt(CURLOPT_USERPWD, "$username:$password");
        }
        return $this;
    }

    /**
     * Permite definir la url Referrer de la conexión
     * @param  string     $referrer     Cadena con la url que se desea asignar como referrer
     * @return self
     */
    final public function setReferrer($referrer)
    {
        if(!empty($referrer))
        {
            $this->setOpt(CURLOPT_REFERER, $referrer);           
        }
        return $this;
    }

    /**
     * Permite definir Cookies que se desean incluir en la conexion
     * @param  string     $key       Cadena con el nombre de la Coohie que se quiere definir
     * @param  string     $value     Cadena con el valor de la cookie
     * @return self
     */
    final public function setCookie($key, $value)
    {
        if(!empty($key)&&!empty($value))
        {
            $this->cookies[$key] = $value;
            $this->setOpt(CURLOPT_COOKIE, http_build_query($this->cookies, '', '; '));
        }        
        return $this;
    }

    /**
     * Permite importar Cookies desde un archivo
     * @param  string     $cookie_file     Cadena de texto con la ruta del archivo que se desea importar
     * @return self
     */
    final public function setCookieFile($cookie_file)
    {
        if(!empty($cookie_file)&&!!file_exists($cookie_file))
        {
            $this->setOpt(CURLOPT_COOKIEFILE, $cookie_file);
        }
        return $this;
    }

    /**
     * Permite definir el archivo donde de desean almacenar las Cookies localmente de manera permanente
     * @param  string     $cookie_jar     Cadena de texto con la ruta del archivo donde se desea almacenar las Cookies
     * @return self
     */
    final public function setCookieJar($cookie_jar)
    {
        if(!empty($cookie_jar)&&!!file_exists($cookie_jar))
        {
            $this->setOpt(CURLOPT_COOKIEJAR, $cookie_jar);
        }
        return $this;
    }

    /**
     * Permite definir el User Agent que se utilizara en la conexion
     * @param  string     $user_agent     Cadena cvon el User Agent
     * @return self
     */
    final public function setUserAgent($user_agent = '')
    {

        $this->setOpt(CURLOPT_USERAGENT, $user_agent);

        return $this;
    }

    /**
     * Permite definir un User Agent por defecto
     * @return self
     */
    private function setDefaultUserAgent()
    {
        $user_agent = 'mppi-curl-php/'. self::VERSION . ' (+https://github.com/ElMijo/php-curl-client)';
        $user_agent .= ' PHP/' . PHP_VERSION;
        $curl_version = curl_version();
        $user_agent .= ' curl/' . $curl_version['version'];
        $this->setUserAgent($user_agent);
        return $this;
    }

    /**
     * Permite validar si un arrays es asociativo
     * @param  Array      $array     Arreglo que se desea evaluar
     * @return boolean               Devuelve TRUE si el arreglo es asociativo o FAÑSE en caso contrario
     */
    protected function is_array_assoc($array = array())
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }

    /**
     * Permite ejecuttar la onsulta cURL
     * @return \PHPTools\PHPCurlClient\Response\PHPCurlClientResponse
     */
    protected function exec()
    {
        $this->raw_response = curl_exec($this->curl);

        $cabeceras = '';

        if (!(strpos($this->raw_response, "\r\n\r\n") === false))
        {
            $parse_raw_response = explode("\r\n\r\n", trim($this->raw_response));

            if(count($parse_raw_response)>2)
            {
                $parse_raw_response = array($parse_raw_response[1],$parse_raw_response[2]);
            }
            list($cabeceras,$this->body_response) = $parse_raw_response;
        }

        $this->extractHeaders($cabeceras);

        return new \PHPTools\PHPCurlClient\Response\PHPCurlClientResponse(
            $this->curl,
            $this->request_headers,
            $this->response_headers,
            $this->body_response
        );
    }
}