<?php

namespace Api;

use Addons\Token\Token;

abstract class BaseApi implements IApi
{
    public string $apiName = '';
    protected mixed $method = '';
    public array $requestUri = [];
    public array $requestParams = [];
    protected string $action = '';
    protected string|Token $token = \stdClass::class;

    public function __construct()
    {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        $this->token = new Token();

        $this->requestUri = explode('/', parse_url(trim($_SERVER['REQUEST_URI'], '/'), PHP_URL_PATH));
        $this->requestParams = $_REQUEST;

        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new \RuntimeException("Unexpected Header");
            }
        }
        $this->run();
    }

    public function run()
    {
        if (array_shift($this->requestUri) !== 'api' || array_shift($this->requestUri) !== $this->apiName) {
            throw new \RuntimeException('API Not Found', 404);
        }

        if ($this->token->isValidateBearer()) {
            $this->action = $this->getAction();

            if (method_exists($this, $this->action)) {
                echo $this->{$this->action}();
            } else {
                throw new \RuntimeException('Invalid Method', 405);
            }
        } else {
            throw new \RuntimeException('Authentication Failed', 401);
        }
    }

    protected function response($data, $status = 500)
    {
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
        return json_encode($data);
    }

    private function requestStatus(int $code): string
    {
        $status = [
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error'
        ];
        return ($status[$code]) ? $status[$code] : $status[500];
    }

    protected function getAction(): ?string
    {
        $method = $this->method;
        switch ($method) {
            case 'GET':
                if ($this->requestUri) {
                    return 'viewAction';
                } else {
                    return 'indexAction';
                }
                break;
            case 'POST':
                return 'createAction';
                break;
            case 'PUT':
                return 'updateAction';
                break;
            case 'DELETE':
                return 'deleteAction';
                break;
            default:
                return null;
        }
    }
}