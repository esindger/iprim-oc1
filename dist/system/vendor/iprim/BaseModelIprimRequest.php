<?php

abstract class BaseModelIprimRequest extends Model
{
    public function findByUid($uid)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_iprim WHERE uid = '" . (string)$uid . "'");

        return $query->num_rows ? $this->populate($query->row) : null;
    }

    public function findByOrder($order_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_iprim WHERE order_id = " . (int)$order_id);

        return $query->num_rows ? $this->populate($query->row) : null;
    }

    public function getActiveOrders()
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_iprim WHERE order_id = 0 AND session_id = '" . session_id() . "'" );

        return array_map(function($item) {
            return $this->populate($item);
        }, $query->rows);
    }

    private function populate($request)
    {
        $request['body_params'] = json_decode($request['body_params'], true);

        return $request;
    }
}
