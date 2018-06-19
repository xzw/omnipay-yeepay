<?php

namespace Omnipay\YeePay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\YeePay\Helper;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class BaseAbstractResponse
 * @package Omnipay\WechatPay\Message
 */
abstract class BaseAbstractResponse extends AbstractResponse
{

    /**
     * @var ParameterBag
     */
    protected $parameters;

    protected $sessionHead = [];

    protected $sessionBody = [];

    public function __construct(RequestInterface $request, $data, $parameters = array())
    {
        $this->request = $request;
        $this->data = $data;
        $this->initialize($parameters);
    }

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     *
     * @return $this
     * @throws RuntimeException
     */
    public function initialize(array $parameters = array())
    {

        if (!Helper::verify($this->data, $this->getKey()))
            throw new InvalidRequestException();

        $session = Helper::xml2array($this->data);

        $this->parameters = new ParameterBag();

        \Omnipay\Common\Helper::initialize($this, array_replace($session, $parameters));

        return $this;
    }


    public function setSessionHead(array $value)
    {
        $this->sessionHead = $value;

        return $this;
    }

    public function getSessionHead()
    {
        return $this->sessionHead;
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


    public function getRespTime()
    {
        $time = $this->getParameter('RespTime',date('YmdHis'));

        return $time;
    }

    public function getBody()
    {
        $data = $this->getData();

        return array_get($data, 'SessionBody');
    }


    public function getServiceCode()
    {
        $sessionHead = $this->getSessionHead();
        return $sessionHead['ServiceCode'];
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->request->getKey();
    }

    public function getParameters()
    {
        return $this->parameters->all();
    }

    protected function getParameter($key, $default = null)
    {
        return $this->parameters->get($key, $default);
    }

    protected function setParameter($key, $value)
    {
        $this->parameters->set($key, $value);

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (preg_match('#set(?<attr>[A-Z].*)#', $name, $matches)) {
            $this->setParameter($matches['attr'], $arguments[0]);
            return $this;
        } else if (preg_match('#get(?<attr>[A-Z].*)#', $name, $matches)) {
            return $this->getParameter($matches['attr']);
        }
    }
}
