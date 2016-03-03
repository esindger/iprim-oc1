<?php

class ModelIprimResponse extends BaseModelIprimResponse
{
    protected function findOrder($order_id)
    {
        $this->load->model('sale/order');

        $order = $this->model_sale_order->getOrder($order_id);

        $order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order['order_status_id'] . "' AND language_id = '" . (int)$order['language_id'] . "'");

        if ($order_status_query->num_rows) {
            $order['order_status'] = $order_status_query->row['name'];
        } else {
            $order['order_status'] = '';
        }

        return $order;
    }
}