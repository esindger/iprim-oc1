<?php

class ModelIprimResponse extends BaseModelIprimResponse
{
    protected function findOrder($order_id)
    {
        $this->load->model('checkout/order');

        return $this->model_checkout_order->getOrder($order_id);
    }
}