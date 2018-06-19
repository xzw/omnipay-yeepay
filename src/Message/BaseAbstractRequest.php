<?php

namespace Omnipay\YeePay\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\YeePay\Helper;

/**
 * Class BaseAbstractRequest
 * @package Omnipay\WechatPay\Message
 */
abstract class BaseAbstractRequest extends AbstractRequest
{

    protected $key;

    protected $endpoint;

    protected $sessionHead = [];

    protected $sessionBody = [];

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->parameters;
    }

    public function validateParams()
    {
        $this->validate('params');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setTimestamp($value)
    {
        return $this->setParameter('timestamp', $value);
    }

    protected function sign($params, $key)
    {
        return '';
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->getParameter('type');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }


    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }


    /**
     * @param mixed $key
     */
    public function setkey($value)
    {
        $this->key = $value;

        return $this;
    }

    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function setReqTime($value)
    {
        $this->setParameter('ReqTime', $value);
        return $this;
    }

    public function getReqTime()
    {
        $time = $this->getParameter('ReqTime', date('YmdHis'));
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        $default = sprintf("%s%s", 'UZEE', microtime());
        return $this->getParameter('TransactionId', $default);
    }


    public function setTransactionId($transactionId)
    {
        $this->setParameter('TransactionId', $transactionId);
    }

    public function setSessionHead(array $value)
    {
        $this->sessionHead = $value;
        return $this;
    }

    public function getSessionHead()
    {
        $default = [
            'Version'       => '1.0',
            'ServiceCode'   => $this->getServiceCode(),
            'TransactionID' => $this->getTransactionId(),
            'SrcSysID'      => 'yeepay',
            'DstSysID'      => 'uzee',
            'ReqTime'       => $this->getReqTime(),
        ];

        return array_replace($default, $this->sessionHead);
    }

    public function setSessionBody(array $value)
    {
        $this->sessionBody = $value;

        return $this;
    }

    public function getSessionBody()
    {
        return $this->sessionBody;
    }

    public function getSession()
    {
        return [
            'SessionHead' => $this->getSessionHead(),
            'SessionBody' => $this->getSessionBody()
        ];
    }

    public function setServiceCode($value)
    {
        $this->setParameter('ServiceCode', $value);

        return $this;
    }

    public function getServiceCode()
    {
        return $this->getParameter('ServiceCode');
    }

    /**
     * @param $data
     *
     * @return string
     */
    protected function getRequestUrl($data)
    {

        $url = $this->getEndpoint();

        return $url;
    }

    /**
     * @return string
     */
    protected function getRequestBody()
    {
        $session = $this->getSession();

        $key = $this->getKey();

        $xml = Helper::array2xml($session);

        $body = Helper::sign($xml, $key);

        return $body;
    }

    protected function getParameter($key, $default = null)
    {
        return $this->parameters->get($key, $default);
    }
}
