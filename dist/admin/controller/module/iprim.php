<?php

class ControllerModuleIprim extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('module/iprim');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('iprim', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        foreach ([
            'heading_title',
            'description',
            'text_edit',
            'entry_secret_key',
            'entry_comment_add',
            'entry_comment_simple',
            'entry_comment_advanced',
            'entry_comment_complete',
            'entry_comment_delete',
            'button_save',
            'button_cancel',
        ]
            as $name) {
            $this->data[$name] = $this->language->get($name);
        }

        foreach (['warning', 'secret_key', 'comment_add', 'comment_simple', 'comment_advanced', 'comment_complete', 'comment_delete'] as $name) {
            if (isset($this->error[$name])) {
                $this->data['error_' . $name] = $this->error[$name];
            } else {
                $this->data['error_' . $name] = '';
            }
        }

        foreach (['secret_key', 'comment_add', 'comment_simple', 'comment_advanced', 'comment_complete', 'comment_delete'] as $name) {
            $this->data['help_' . $name] = $this->language->get('help_' . $name);
        }

        foreach (['secret_key', 'comment_add', 'comment_simple', 'comment_advanced', 'comment_complete', 'comment_delete'] as $name) {
            if (isset($this->request->post['iprim_' . $name])) {
                $this->data['iprim_' . $name] = $this->request->post['iprim_' . $name];
            } else {
                $this->data['iprim_' . $name] = $this->config->get('iprim_' . $name);
            }
        }

        $this->data['breadcrumbs'] = [];

        $this->data['breadcrumbs'][] = [
            'separator' => '',
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $this->data['breadcrumbs'][] = [
            'separator' => ' :: ',
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $this->data['breadcrumbs'][] = [
            'separator' => ' :: ',
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/iprim', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $this->data['action'] = $this->url->link('module/iprim', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        $this->load->model('design/layout');

        $this->data['layouts'] = $this->model_design_layout->getLayouts();

        $this->template = 'module/iprim.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    public function install()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "order_iprim` (
          `uid` VARCHAR(32) NOT NULL,
          `order_id` INT(11) DEFAULT '0',
          `customer_id` INT(11) DEFAULT '0',
          `session_id` VARCHAR(32) NOT NULL,
          `body_params` TEXT NOT NULL,
          `date_added` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`uid`),
          KEY `order_id` (`order_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('iprim', [
            'iprim_secret_key' => '',
            'iprim_comment_add' => 'Заказ оформлен',
            'iprim_comment_simple' => '',
            'iprim_comment_advanced' => '@comment',
            'iprim_comment_complete' => '@status',
            'iprim_comment_delete' => 'Заказ удален',
        ]);
    }

    public function uninstall()
    {
        $this->db->query("DROP TABLE `" . DB_PREFIX . "order_iprim`;");
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('iprim');
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'module/iprim')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!trim($this->request->post['iprim_secret_key'])) {
            $this->error['secret_key'] = $this->language->get('error_required');
        } elseif (utf8_strlen($this->request->post['iprim_secret_key']) != 32) {
            $this->error['secret_key'] = $this->language->get('error_length');
        }

        return !$this->error;
    }
}