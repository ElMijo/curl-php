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
class PHPCurlClientResponse
{
    /**
     * Patron utilizado para validar los contenidos JSON
     */
    const PATRON_JSON = '/^application\/json/i';

    /**
     * Patron utilizado para validar los contenidos atom/xml
     */
    const PATRON_ATOM_XML = '/^application\/atom\+xml/i';

    /**
     * Patron utilizado para validar los contenidos rss/xml
     */
    const PATRON_RSS_XML  = '/^application\/rss\+xml/i';

    /**
     * Patron utilizado para validar los contenidos aplication/xml
     */
    const PATRON_APP_XML  = '/^application\/xml/i';

    /**
     * Patron utilizado para validar los contenidos text/xml
     */
    const PATRON_TEXT_XML = '/^text\/xml/i';

    /**
     * Cabeceras de la peticion
     * @var array
     */
    public $request_headers = array();

    /**
     * Cabeceras de la Respuesta
     * @var array
     */
    public $response_headers = array();

    /**
     * Cuerpo de la respuesta de la peticion
     * @var string
     */
    public $body = '';

    /**
     * Objeto error
     * @var \PHPTools\PHPCurlClient\Response\PHPCurlClientError
     */
    public $error;

    function __construct($curl, $request_headers,$response_headers,$body)
    {
        $this->request_headers = $request;
        $this->response_headers = $response;
        $this->body = $body;

        $this->error = new \PHPTools\PHPCurlClient\Response\PHPCurlClientError($curl,$response_headers);

        $this->parserBody();
    }

    /**
     * Permite procesar el cuerpo de la respuesta y separar las cabeceras del curpo de la respuesta
     * @return void
     */
    private function parserBody()
    {
        if(isset($this->response_headers['Content-Type']))
        {
            $content_type = $this->response_headers['Content-Type'];

            if($this->esJSON($content_type))
            {
                $this->body = json_decode($this->body, false);
            }
            elseif ($this->esXML($content_type))
            {
                $this->body = @simplexml_load_string($this->body);
            }
        }
    }

    /**
     * Permite saber si un tipo de contenido es JSON
     * @param  String     $tipo_contenido     Cadena con el contenido a evaluar
     * @return Boolean                        Devuelve TRUE si el Contenido es JSON o FALSE en caso contrario
     */
    private function esJSON($content_type)
    {
        return preg_match(self::PATRON_JSON, $content_type);
    }

    /**
     * Permite saber si un tipo de contenido es XML
     * @param  String     $tipo_contenido     Cadena con el contenido a evaluar
     * @return Boolean                        Devuelve TRUE si el Contenido es XML o FALSE en caso contrario
     */
    private function esXML($content_type)
    {
        return !!(preg_match(self::PATRON_ATOM_XML, $content_type)||
            preg_match(self::PATRON_RSS_XML, $content_type)||
            preg_match(self::PATRON_APP_XML, $content_type)||
            preg_match(self::PATRON_TEXT_XML, $content_type)
        );
    }
}