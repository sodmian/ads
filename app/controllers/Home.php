<?php

namespace Controllers;

class Home extends \Core\Presenter
{
    public function view()
    {
        $this->load->model('Ads');
        $this->view->render('home', ['data' => $this->model->Ads->getAds()], ['assets/js/home.js']);
    }
}