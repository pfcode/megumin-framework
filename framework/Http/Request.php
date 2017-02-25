<?php

namespace pfcode\MeguminFramework\Http;

use pfcode\MeguminFramework\Architecture\Arr;

class Request
{
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_HEAD = 'HEAD';

    const METHODS = [
        self::METHOD_DELETE,
        self::METHOD_GET,
        self::METHOD_HEAD,
        self::METHOD_POST,
        self::METHOD_PUT
    ];

    private $type;
    private $params = [];

    /**
     * Request constructor.
     *
     * @param array $params
     * @param null $type
     */
    public function __construct(array $params = [], $type = null)
    {
        if ($type === null) {
            $this->type = $this->type();
        } else {
            $this->type = $type;
        }

        if (is_array($params)) {
            $this->params = $params;
        }
    }

    /**
     * Retrieve parameter from request body.
     * Note that $_GET and $_POST super-globals are merged.
     * By default $_POST has priority over $_GET parameters,
     * except when request type is GET - then priority is opposite.
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($this->type == self::METHOD_GET) {
            // When used method is GET then $_GET params have priority on $_POST content
            $primaryParams = $_GET;
            $secondaryParams = $_POST;
        } else {
            $primaryParams = $_POST;
            $secondaryParams = $_GET;
        }

        if (isset($primaryParams[$key])) {
            return $primaryParams[$key];
        } else if (isset($secondaryParams[$key])) {
            return $secondaryParams[$key];
        } else {
            return $default;
        }
    }

    /**
     * Retrieve sanitized type of HTTP request
     * @return string|null
     */
    public function type(): string
    {
        $rawMethod = Arr::get($_SERVER, "REQUEST_METHOD", null);

        if (in_array($rawMethod, self::METHODS)) {
            return $rawMethod;
        }

        return null;
    }

    /**
     * Retrieve parameter set by Router
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function param($key, $default = null)
    {
        return Arr::get($this->params, $key, $default);
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function cookie($key, $default = null)
    {
        return Cookies::get($key, $default);
    }
}