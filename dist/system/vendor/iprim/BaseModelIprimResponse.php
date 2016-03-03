<?php

abstract class BaseModelIprimResponse extends Model
{
    abstract protected function findOrder($order_id);

    public function handleChange($order_id)
    {
        if ($order = $this->findOrder($order_id) and $order['order_status_id']) {
            $is_new = false;
            if ($requests = $this->filterRequests($this->findRequests(), $order)) {
                $request = array_pop($requests);
                $this->db->query("UPDATE `" . DB_PREFIX . "order_iprim` SET order_id = " . $order_id . " WHERE uid = '" . $request['uid'] . "'");

                if ($requests) {
                    foreach ($requests as $item) {

                        $this->db->query("UPDATE `" . DB_PREFIX . "order_iprim` SET order_id = " . $order_id . " WHERE uid = '" . $item['uid'] . "'");

                        $params = [
                            'internal_id' => $order_id,
                            'status'      => \iprim\market\Client\Response::STATUS_CANCEL,
                            'comment'     => 'Заказ объединен с заказом #' . $request['body_params']['order']['id'],
                            'notify'      => 0,
                            'background'  => 1,
                        ];
                        $this->send($item['uid'], $params);
                    }
                }
                $is_new = true;
            } else {
                $request = $this->findRequest($order_id);
            }

            $changes = $this->getLastStatus($order_id);

            $complete_status = (array)$this->config->get('config_complete_status_id');
            $return_status = (array)$this->config->get('config_return_status_id');
            $processing_status = (array)$this->config->get('config_order_status_id');

            $status = $comment = null;
            if (in_array($order['order_status_id'], $complete_status)) {
                $status = \iprim\market\Client\Response::STATUS_COMPLETE;
                $comment = $this->config->get('iprim_comment_complete');
            } elseif (in_array($order['order_status_id'], $processing_status)) {
                $status = \iprim\market\Client\Response::STATUS_PROCESS;
            } elseif (in_array($order['order_status_id'], $return_status)) {
                $status = \iprim\market\Client\Response::STATUS_CANCEL;
            }

            if (!$comment) {
                if ($status !== \iprim\market\Client\Response::STATUS_CANCEL and $is_new) {
                    $comment = $this->config->get('iprim_comment_add');
                } elseif ($changes['comment']) {
                    $comment = $this->config->get('iprim_comment_advanced');
                } else {
                    $comment = $this->config->get('iprim_comment_simple');
                }
            }

            $comment = str_replace('@status', $order['order_status'], $comment);
            $comment = str_replace('@comment', $changes['comment'], $comment);

            $params = [
                'internal_id' => $order_id,
                'status'      => $status,
                'status_text' => $order['order_status'],
                'comment'     => $comment,
                'notify'      => ($is_new or $changes['notify']) ? 1 : 0,
                'background'  => $is_new ? 0 : 1,
            ];

            if ($is_new) {
                $this->session->data['iprim']['response'] = [
                    'uid'    => $request['uid'],
                    'params' => $params,
                ];
            } else {
                $this->send($request['uid'], $params);
            }
        }
    }

    public function handleDelete($order_id)
    {
        if ($order = $this->findOrder($order_id) and $request = $this->findRequest($order_id)) {

            $comment = $this->config->get('iprim_comment_delete');
            $comment = str_replace('@status', $order['order_status'], $comment);

            $params = [
                'status'      => \iprim\market\Client\Response::STATUS_CANCEL,
                'status_text' => $order['order_status'],
                'comment'     => $comment,
                'notify'      => 1,
                'background'  => 1,
            ];

            $this->send($request['uid'], $params);
        }
    }

    public function send($uid, array $params)
    {
        $client = Iprim::marketClient();

        if (!$secret_key = $this->config->get('iprim_secret_key')) {
            $response = $client->response;
            $response->uid = $uid;
            $response->send();

            trigger_error('The market.iprim.ru secret key does not specified. Check the module settings.', E_USER_WARNING);

            return false;
        }

        \iprim\market\Client\Config::$secretKey = $secret_key;

        try {
            $response = $client->response;
            $response->uid = $uid;
            foreach ($params as $name => $value) {
                $response->$name = $value;
            }
            $response->send();

        } catch (\iprim\market\common\Exception $e) {
            $response = $client->response;
            $response->uid = $uid;
            $response->exception = $e;
            $response->send();
        }
    }

    private function findRequest($order_id)
    {
        $this->load->model('iprim/request');

        return $this->model_iprim_request->findByOrder($order_id);
    }

    private function findRequests()
    {
        $this->load->model('iprim/request');

        return $this->model_iprim_request->getActiveOrders();
    }

    private function filterRequests($requests, $order)
    {
        $product_ids = array_map(function ($product) {
            return $product['product_id'];
        }, $this->getOrderProducts($order));

        $result = [];
        foreach ($requests as $request) {
            foreach ($request['body_params']['basket'] as $item) {
                if (in_array($item['offer']['source_id'], $product_ids)) {
                    $result[] = $request;
                }
            }
        }

        return $result;
    }

    private function getLastStatus($order_id)
    {
        $query =
            $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added DESC LIMIT 1");

        return $query->row;
    }

    private function getOrderProducts($order)
    {
        $query =
            $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = " . $order['order_id']);

        return $query->rows;
    }

}