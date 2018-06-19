<?php
/**
 * Created by PhpStorm.
 * User: Master
 * Date: 2018/3/19
 * Time: 17:58
 */

namespace Omnipay\YeePay;


use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\YeePay\Message\CompletePurchaseRequest;
use Omnipay\YeePay\Message\QueryOrderRequest;

class PosAbstractGateway extends AbstractGateway
{
    protected $endpoints = [
        'production' => '',
        'sandbox'    => '',
    ];

    public function getName()
    {
        return '';
    }

    public function getDefaultParameters()
    {
        return [
            'format'    => 'XML',
            'charset'   => 'UTF-8',
            'signType'  => 'MD5',
            'version'   => '1.0',
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    }

    public function setVersion($version)
    {
        $this->setParameter('Version', $version);
    }

    public function setKey($key)
    {
        $this->setParameter('Key', $key);
    }

    public function getKey()
    {
        return $this->getParameter('Key');
    }

    public function production()
    {
        return $this->setEnvironment('production');
    }


    /**
     * @param $value
     *
     * @return $this
     * @throws InvalidRequestException
     */
    public function setEnvironment($value)
    {
        $env = strtolower($value);

        if (!isset($this->endpoints[$env])) {
            throw new InvalidRequestException('The environment is invalid');
        }

        $this->setEndpoint($this->endpoints[$env]);

        return $this;
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setEndpoint($value)
    {
        return $this->setParameter('endpoint', $value);
    }

    public function getEndpoint()
    {
        return $this->getParameter('endpoint');
    }

    public function sandbox()
    {
        return $this->setEnvironment('sandbox');
    }


    public function completePurchase($parameters = array())
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }


    public function query($parameters = array())
    {
        return $this->createRequest(QueryOrderRequest::class, $parameters);
    }


    public function close($parameters = array())
    {

    }


    public function refund($parameters = array())
    {

    }


    public function queryRefund($parameters = array())
    {

    }

}