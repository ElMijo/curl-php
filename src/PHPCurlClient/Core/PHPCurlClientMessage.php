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
class PHPCurlClientMessage
{
    const NO_EXTENCION_CURL = 'Al parecer la extención php-curl no esta disponible';

    const OPCION_NECESARIA  = 'La Opción [%s] ss necesaria para la buena ejecución de cURL';
}