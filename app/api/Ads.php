<?php

namespace Api;

use Core\Model;

class Ads extends \Api\BaseApi
{
    public string $apiName = 'ads';

    public function indexAction(): string
    {
        //TODO: Реализуем GET запрос к БД и отдаем данные в JSON
        return $this->response($this->requestParams, 200);
    }

    public function viewAction(): string
    {
        //TODO: Реализуем GET запрос к БД и отдаем данные в JSON
        return $this->response($this->requestParams, 200);
    }

    public function createAction(): string
    {
        //TODO: Реализуем POST запрос к БД и отдаем данные в JSON
        return $this->response($this->requestParams, 200);
    }

    public function updateAction(): string
    {
        //TODO: Реализуем PUT запрос к БД и отдаем данные в JSON
        return $this->response($this->requestParams, 200);
    }

    public function deleteAction(): string
    {
        //TODO: Реализуем DELETE запрос к БД и отдаем данные в JSON
        return $this->response($this->requestParams, 200);
    }

}