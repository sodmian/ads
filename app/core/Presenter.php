<?php

namespace Core;

use Dotenv\Dotenv;

abstract class Presenter
{
    protected ?\PDO $db;
    protected View $view;
    protected Presenter $load;
    protected \stdClass $model;

    function __construct()
    {
        Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'], '.env')->load();

        $this->db = Model::getInstance();
        $this->view = new View();
        $this->load = $this;
        $this->model = new \stdClass();
    }

    function model(string $name)
    {
        $models = explode(',', preg_replace('/\s*/', '', $name));
        foreach ($models as $model) {
            $nameWithNamespace = "Models\\" . $model;
            if (class_exists($nameWithNamespace)) {
                $this->model->{$model} = new $nameWithNamespace($this->db);
            } else {
                throw new \RuntimeException("Не удалось загрузить модель `$model`.");
            }
        }
    }
}
