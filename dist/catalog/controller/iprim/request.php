<?php

class ControllerIprimRequest extends Controller
{
    public function index()
    {
        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            exit('The ' . $_SERVER['REQUEST_METHOD'] . ' method is not allowed.');
        }
        $this->load->model('iprim/request');
        $this->model_iprim_request->save();
        $this->response->redirect($this->url->link('checkout/checkout'));
    }
}
