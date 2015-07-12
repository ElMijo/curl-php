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
class PHPCurlClientBase
{
    /**
    * Versión de la clase
    */
    const VERSION = '1.0';

    /**
    * Respuesta de la ejecucion del objeto CuRL
    * @var String
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


    private $default_options = array(
        CURLINFO_HEADER_OUT    => 'CURLINFO_HEADER_OUT',
        CURLOPT_HEADER         => 'CURLOPT_HEADER',
        CURLOPT_RETURNTRANSFER => 'CURLOPT_RETURNTRANSFER'
    );
    /**
    * Objeto CuRL
    * @var CuRL
    */
    private $curl = NULL;

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

    /**
    * Permite definir opociones propias de CuRL
    * @param String                     $option     Cadena con el nombre de la opcion que se desea definir
    * @param String|Boolean|Numeric     $value      Valor que se desea definir
    * @param Boolean                                Devuelve TRUE si la operacion se realizo corectamente o FALSE en caso contrario
    */
    public function setOpt($option, $value)
    {

        $set_opt = False,
        if (in_array($option, array_keys($opciones_requeridas), true) && !($value === true))
        {
            $msg = sprintf(MSG::OPCION_NECESARIA,$this->default_options[$opcion],PEL_WARNING);

            $this->logger($msg);
        }
        else
        {
            $this->opciones[$opcion] = $value;

            $set_opt = curl_setopt($this->curl, $opcion, $value);
        }
        return $set_opt;
    }

    /**
    * Permite definir el User Agent que se utilizara en la conexion
    * @param  String     $user_agent     Cadena cvon el User Agent
    * @return CurlPhp
    */
    public function setUserAgent($user_agent = '')
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
}