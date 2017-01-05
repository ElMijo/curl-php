<?php
/**
 * This file is part of the PhpCurl package.
 *
 * (c) Jerry Anselmi <jerry.anselmi@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhpTools\PhpCurl\Core;

use InvalidArgumentException;

/**
 * Basic structure
 * @author Jerry Anselmi <jerry.anselmi@gmail.com>
 */
abstract class PhpCurlAware
{
    /**
     * Options
     * @var array
     */
    private $options = [];

    /**
     * Headers
     * @var array
     */
    private $headers = [];

    /**
     * Cookies
     * @var array
     */
    private $cookies = [];

    /**
     * Protected options
     * @var array
     */
    private $protectedOptions = [
        CURLINFO_HEADER_OUT,
        CURLOPT_HEADER,
        CURLOPT_RETURNTRANSFER,
        CURLOPT_HTTPHEADER,
        CURLOPT_HTTPAUTH,
        CURLOPT_USERPWD,
        CURLOPT_USERAGENT,
        CURLOPT_REFERER,
        CURLOPT_COOKIE,
        CURLOPT_COOKIEFILE,
        CURLOPT_COOKIEJAR,
        CURLOPT_POST,
        CURLOPT_POSTFIELDS,
        CURLOPT_NOBODY,
        CURLOPT_CUSTOMREQUEST,
        CURLOPT_URL,
    ];

    /**
     * Allows to define the User Agent that is used in the connection.
     * @param  string $userAgent Text string with User Agent.
     * @return self
     */
    final public function setUserAgent($userAgent)
    {
        if (!$this->validteString($userAgent)) {
            throw new InvalidArgumentException(sprintf(
                'User Agent, $userAgent [%s] must be a string.',
                $userAgent
            ));
        }

        $this->setProtectedOption(CURLOPT_USERAGENT, $userAgent);
        return $this;
    }

    /**
     * Allows you to remove User Agent
     * @return self
     */
    final public function removeUserAgent()
    {
        $this->removeProtectedOption(CURLOPT_USERAGENT);
        return $this;
    }

    /**
     * Allows to add options of CuRL.
     * @param string $option String with the name of the option to be defined.
     * @param mixed  $value  Value to be defined.
     * @param self
     */
    final public function addOption($option, $value)
    {
        if (!$this->validteString($option)) {
            throw new InvalidArgumentException(sprintf(
                'Curl Option, $option [%s] must be a string.',
                $option
            ));
        }

        if (!in_array($option, $this->protectedOptions)) {
            $this->options[$option] = $value;
        }
        return $this;
    }

    /**
     * This method lets you know if there is a defined option.
     * @param  string $option String with the name of the option.
     * @return boolean
     */
    final public function hasOption($option)
    {
        return isset($this->opciones[$option]);
    }

    /**
     * Allows to reove own options of CuRL.
     * @param string $option String with the name of the option you want to remove.
     * @param self
     */
    final public function removeOption($option)
    {
        if (isset($this->options[$option])&&!in_array($option, $this->protectedOptions)) {
            unset($this->options[$option]);
        }
        return $this;
    }

    /**
     * It allows to obtain all options.
     * @return array
     */
    final public function getOptions()
    {
        return $this->opciones;
    }


    /**
     * Defines the headers that must be included in the connection.
     * @param  array $headers Associative array of headers
     * @return self
     */
    final public function addHeader($headers = array())
    {
        $this->headers = array_merge(
            $this->headers,
            array_filter($headers, 'is_string', ARRAY_FILTER_USE_KEY)
        );

        return $this;
    }

    /**
     * Allows you to remove headers
     * @param  string $key String with the name of the header to be deleted
     * @return self
     */
    final public function removeHeader($key)
    {
        if (isset($this->headers[$key])) {
            unset($this->headers[$key]);
        }
        return $this;
    }

    /**
     * It allows to obtain all headers.
     * @return array
     */
    final public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Permite definir el metodo de autenticación basica como metodo de autenticacion
     * @param  string $username Cadena con el nombre de usuario
     * @param  string $password Cadena con la clave
     * @return self
     */
    final public function setBasicAuth($username, $password)
    {
        if (!$this->validteString($username)) {
            throw new InvalidArgumentException(sprintf(
                'Basic authentication, $username [%s] must be a string.',
                $username
            ));
        }

        if (!$this->validteString($password)) {
            throw new InvalidArgumentException(sprintf(
                'Basic authentication, $password [%s] must be a string.',
                $username
            ));
        }

        $this->setProtectedOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $this->setProtectedOption(CURLOPT_USERPWD, "$username:$password");

        return $this;
    }

    /**
     * Allows you to remove headers
     * @return self
     */
    final public function removeBasicAuth()
    {
        $this
            ->removeProtectedOption(CURLOPT_HTTPAUTH)
            ->removeProtectedOption(CURLOPT_USERPWD)
        ;
        return $this;
    }

    /**
     * Set the url Referer.
         * @param  string $referrer String with the url to be assigned as referer
     * @return self
     */
    final public function setReferrer($referrer)
    {
        if (!filter_var($referrer, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(sprintf(
                'Referrer, $referrer [%s] must be a url.',
                $referrer
            ));
        }
        $this->setProtectedOption(CURLOPT_REFERER, $referrer);

        return $this;
    }

    /**
     * Allows you to remove headers
     * @return self
     */
    final public function removeReferrer()
    {
        $this->removeProtectedOption(CURLOPT_REFERER);
        return $this;
    }

    /**
     * Defines the headers that must be included in the connection.
     * @param  array $headers Associative array of cookies.
     * @return self
     */
    final public function addCookies($cookies = array())
    {
        $this->cookies = array_merge(
            $this->cookies,
            array_filter($cookies, 'is_string', ARRAY_FILTER_USE_KEY)
        );

        return $this;
    }

    /**
     * Allows you to remove headers
     * @param  string $key String with the name of the header to be deleted
     * @return self
     */
    final public function removeCookie($key)
    {
        if (isset($this->cookies[$key])) {
            unset($this->cookies[$key]);
        }
        return $this;
    }

    /**
     * It allows to obtain all options.
     * @return array
     */
    final public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Allows you to import cookies from a file.
     * @param  string $filename String with the path of the file to be imported.
     * @return self
     */
    final public function setCookieFile($filename)
    {
        if (!is_readable($filename)) {
            throw new InvalidArgumentException(sprintf(
                'Curl Cookie File, The file [%s] does not exist or you can not have read permissions.',
                $filename
            ));
        }
        $this->setProtectedOption(CURLOPT_COOKIEFILE, $filename);
        return $this;
    }

    /**
     * Remove cookie file
     * @return self
     */
    final public function removeCookieFile()
    {
        $this->removeProtectedOption(CURLOPT_COOKIEFILE);
        return $this;
    }

    /**
     * Permite definir el archivo donde de desean almacenar las Cookies localmente de manera permanente
     * @param  string     $filename     Cadena de texto con la ruta del archivo donde se desea almacenar las Cookies
     * @return self
     */
    final public function setCookieJar($filename)
    {
        if (!is_writable($filename)) {
            throw new InvalidArgumentException(sprintf(
                'Curl Cookie Jar, The file [%s]  does not exist or you can not have write permissions.',
                $filename
            ));
        }
        $this->setProtectedOption(CURLOPT_COOKIEJAR, $filename);
        return $this;
    }

    /**
     * Remove cookie jar file
     * @return self
     */
    final public function removeCookieJar()
    {
        $this->removeProtectedOption(CURLOPT_COOKIEJAR);
        return $this;
    }

    /**
     * Allows to define internal options of CuRL.
     * @param string $option String with the name of the option to be defined.
     * @param mixed  $value  Value to be defined.
     * @param self
     */
    private function setProtectedOption($option, $value)
    {
        $this->options[$option] = $value;
        return $this;
    }
    /**
     * Allows to remove intrnal options of CuRL.
     * @param string $option String with the name of the option you want to remove.
     * @param self
     */
    private function removeProtectedOption($option)
    {
        if (isset($this->options[$option])) {
            unset($this->options[$option]);
        }
        return $this;
    }
    /**
     * Validate a string
     * @param  string $val
     * @return string|null
     */
    private function validteString($val)
    {
        return is_string($val)&&!empty($val) ? $val : null;
    }
}
