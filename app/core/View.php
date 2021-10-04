<?php

namespace Core;

use Addons\Token\Token;

class View
{
    protected \Twig\Environment $twig;

    public function __construct()
    {
        $loaderTwig = new \Twig\Loader\FilesystemLoader('templates');
        $this->twig = new \Twig\Environment($loaderTwig, [
            'debug' => true,
            //'cache' => 'cache/templates'
        ]);
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());

        // TODO: Нужен метод инициализации глобальных переменных шаблона
        $this->twig->addGlobal('csrfToken', (new Token())->generateToken());
        $this->twig->addGlobal('env', [
            'APP_CSV_HEADER_LEN' => $_ENV['APP_CSV_HEADER_LEN'],
            'APP_CSV_CONTACTS_LEN' => $_ENV['APP_CSV_CONTACTS_LEN']
        ]);
    }

    public function display(string $template, $data = [])
    {
        try {
            return $this->twig->render($template . '.twig', $data);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function render(string $template, $data = [], $scripts = [])
    {
        try {
            echo $this->twig->render('layout.twig', array_merge([
                '_content' => $template . '.twig',
                '_scripts' => $scripts
            ], $data));
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}