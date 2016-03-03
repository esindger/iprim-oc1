<?php
namespace iprim\market;

use iprim\market\Client\Request;
use iprim\market\Client\Response;
use iprim\market\common\Exception;
use iprim\market\common\Object;

/**
 * @property Request $request
 * @property Response response
 */
class Client extends Object
{
    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var Response
     */
    protected $_response;

    /**
     * @return Request
     */
    public function getRequest()
    {
        if ($this->_request === null) {
            $this->setRequest();
        }

        return $this->_request;
    }

    /**
     * @param array $bodyParams
     * @return Request
     * @throws Exception
     */
    public function setRequest(array $bodyParams = null)
    {
        if ($this->_request !== null) {
            throw new Exception('The request has already been initialized');
        }
        if ($bodyParams === null) {
            $bodyParams = $_POST;
        }

        return $this->_request = new Request($bodyParams);
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        if ($this->_response === null) {
            $this->setResponse();
        }

        return $this->_response;
    }

    /**
     * @param array $config
     * @return Response
     */
    public function setResponse(array $config = [])
    {
        return $this->_response = new Response($config);
    }
}
