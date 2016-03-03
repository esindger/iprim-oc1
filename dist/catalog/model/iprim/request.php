<?php

class ModelIprimRequest extends BaseModelIprimRequest
{
    /**
     * @return \iprim\market\Client\Request
     * @throws \iprim\market\common\InvalidConfigException
     */
    public function save()
    {
        $client = Iprim::marketClient();

        try {
            if (!$secret_key = $this->config->get('iprim_secret_key')) {
                $error = 'The market.iprim.ru secret key does not specified. Check the module settings.';
                trigger_error($error, E_USER_WARNING);
                throw new \iprim\market\common\Exception($error);
            }

            \iprim\market\Client\Config::$secretKey = $secret_key;

            /** @var \iprim\market\Client\Request $request */
            $request = $client->request;

            /** @var Customer $customer */
            $customer = $this->customer;

            if($this->findByUid($request->uid)) {
                $this->load->model('iprim/response');
                $this->model_iprim_response->send($request->uid, [
                    'exception' => new \iprim\market\common\Exception('The request has been expired.')
                ]);
            }

            $this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "order_iprim` SET `uid` = '" . $request->uid . "', `customer_id` = '" . $customer->getId() . "', `session_id` = '" . session_id() . "', `body_params` = '" . json_encode($request->toArray()) . "', `date_added` = NOW()");

            foreach ($request->basket as $item) {
                $this->add2cart($item);
            }

            if (!$this->customer->isLogged()) {

                $customer_name = preg_split('~\s+~', trim($request->order->customer_name));

                if (!isset($this->session->data['guest']['lastname']) or
                    !$this->session->data['guest']['lastname']
                ) {
                    $this->session->data['guest']['lastname'] = isset($customer_name[0]) ? $customer_name[0] : '';
                }
                if (!isset($this->session->data['guest']['firstname']) or
                    !$this->session->data['guest']['firstname']
                ) {
                    $this->session->data['guest']['firstname'] = isset($customer_name[1]) ? $customer_name[1] : '';
                }
                if (!isset($this->session->data['guest']['telephone']) or
                    !$this->session->data['guest']['telephone']
                ) {
                    $this->session->data['guest']['telephone'] = $request->order->customer_phone;
                }
                if (!isset($this->session->data['guest']['email'])
                    or !$this->session->data['guest']['email']
                ) {
                    $this->session->data['guest']['email'] = $request->order->customer_email;
                }
                if (!isset($this->session->data['guest']['payment']['address_1']) or
                    !$this->session->data['guest']['payment']['address_1']
                ) {
                    $this->session->data['guest']['payment']['country_id'] = $this->config->get('config_country_id');
                    $this->session->data['guest']['payment']['city'] = $request->delivery->region ?: '';
                    $this->session->data['guest']['payment']['address_1'] = $request->delivery->address ?: '';
                    $this->session->data['guest']['payment']['zone_id'] = '';
                }
                if($request->order->customer_comment) {
                    $this->session->data['comment'] = $request->order->customer_comment;
                }
                $this->session->data['account'] = 'guest';
            }

            return $request;

        } catch (\iprim\market\common\Exception $e) {
            $response = $client->response;
            $response->uid = @$_POST['request']['request']['uid'];
            $response->exception = $e;
            $response->send();
            exit('Bad request.');
        }
    }

    /**
     * @param \iprim\market\Client\Request\Basket $item
     */
    private function add2cart($item)
    {
        $product_id = $item->offer->source_id;
        $quantity = $item->number;

        $this->load->model('catalog/product');
        $product_info = $this->model_catalog_product->getProduct($product_id);

        if ($product_info) {

            if ($quantity < $product_info['minimum']) {
                $quantity = $product_info['minimum'] ? $product_info['minimum'] : 1;
            }

            $this->cart->add($product_id, $quantity, [], 0);
        }
    }
}