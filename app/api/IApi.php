<?php

namespace Api;

interface IApi
{
    /**
     * Метод GET
     * Вывод списка всех записей
     * http(s)://[ДОМЕН]/api/[ИМЯ_КОНТРОЛЛЕРА]
     * @return string
     */
    function indexAction(): string;

    /**
     * Метод GET
     * Просмотр отдельной записи (по id)
     * http(s)://[ДОМЕН]/api/[ИМЯ_КОНТРОЛЛЕРА]/[ID]
     * @return string
     */
    function viewAction(): string;

    /**
     * Метод POST
     * Создание новой записи
     * http(s)://[ДОМЕН]/api/[ИМЯ_КОНТРОЛЛЕРА] + параметры запроса
     * @return string
     */
    function createAction(): string;

    /**
     * Метод PUT
     * Обновление отдельной записи (по ее id)
     * http(s)://[ДОМЕН]/api/[ИМЯ_КОНТРОЛЛЕРА]/[ID] + параметры запроса
     * @return string
     */
    function updateAction(): string;

    /**
     * Метод DELETE
     * Удаление отдельной записи (по ее id)
     * http(s)://[ДОМЕН]/api/[ИМЯ_КОНТРОЛЛЕРА]/[ID]
     * @return string
     */
    function deleteAction(): string;
}