<?php
/**
 * This file is part of the PhpCurl package.
 *
 * (c) Jerry Anselmi <jerry.anselmi@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhpTools\PhpCurl\Request;

use PhpTools\PhpCurl\Exception\NotAvailableExtensionException;
use PhpTools\PhpCurl\HttpCurl;
use InvalidArgumentException;

/**
 * @author Jerry Anselmi <jerry.anselmi@gmail.com>
 */
class HttpCurlRequest
{
    /**
     * Objeto CuRL
     * @var resource
     */
    protected $curl;

    /**
     * @var \PhpTools\PhpCurl\HttpCurl
     */
    protected $httpCurl;

    /**
     * Method of the request
     * @var string
     */
    protected $method;

    /**
     * Url of the request
     * @var string
     */
    protected $url;

    /**
     * Parameters of the request
     * @var array
     */
    protected $parameters;

    /**
     * Lista de metodos disponibles paraa realizar una petición.
     * @var array
     */
    private $allowMethods = ['OPTIONS', 'GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'TRACE'];

    public function __construct(HttpCurl $httpCurl, $method, $url, $parameters)
    {
        if (!is_subclass_of($this, 'PhpTools\PhpCurl\Core\PhpCurlAware')) {
            throw new RuntimeException(sprintf(
                "Class %s must implement class %s",
                get_class($this),
                "PhpTools\PhpCurl\Core\PhpCurlAware"
            ));
        }

        if (!extension_loaded('curl')) {
            throw new NotAvailableExtensionException();
        }
        $this->httpCurl = $httpCurl;
        $this->curl = curl_init();
        $this->setMethod($method);
        $this->setUrl($url);
        $this->setParameters($parameters);

        // $this->setDefaultUserAgent();
        // $this->setOpt(CURLINFO_HEADER_OUT, true);
        // $this->setOpt(CURLOPT_HEADER, true);
        // $this->setOpt(CURLOPT_RETURNTRANSFER, true);
    }

    public function __destruct()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    /**
     * Set the request type.
     * @param string $method
     * @return self
     *
     * @throw InvalidArgumentException
     */
    private function setMethod($method)
    {
        $method = strtoupper($method);
        if (!in_array($method, $this->allowMethods)) {
            throw new InvalidArgumentException(sprintf(
                'Method [%s] is not supported.',
                $method
            ));
        }
        $this->method = $method;
        return $this;
    }

    /**
     * Set the url request.
     * @param string $url
     * @return self
     *
     * @throw InvalidArgumentException
     */
    private function setUrl($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(sprintf(
                '[%s] must be a url.',
                $url
            ));
        }
        $this->url = $url;
        return $this;
    }

    /**
     * Set the parameters request.
     * @param string $url
     * @return self
     *
     * @throw InvalidArgumentException
     */
    private function setParameters($parameters)
    {
        $this->parameters = array_merge(
            $this->parameters,
            array_filter($parameters, 'is_string', ARRAY_FILTER_USE_KEY)
        );
        return $this;
    }


}
