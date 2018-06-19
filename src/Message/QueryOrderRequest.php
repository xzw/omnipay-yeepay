<?php

namespace Omnipay\YeePay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\YeePay\Helper;

class QueryOrderRequest extends BaseAbstractRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validateParams();

        if (!$this->getOrderNo() && !$this->getReferNo()) {
            throw new InvalidRequestException("The 'OrderNo' or 'ReferNo' parameter is required");
        }

        return $this->parameters;
    }

    public function getOrderNo()
    {
        return $this->getParameter('OrderNo');
    }

    public function setOrderNo($orderNo)
    {
        $this->setParameter('OrderNo', $orderNo);
    }

    public function getReferNo()
    {
        return $this->getParameter('ReferNo');
    }

    public function setReferNo($referNo)
    {
        $this->setParameter('ReferNo', $referNo);
    }

    public function getCustomerNo()
    {
        return $this->getParameter('CustomerNo');
    }

    public function setCustomerNo($customerNo)
    {
        $this->setParameter('CustomerNo', $customerNo);
    }

    public function validateParams()
    {
        $this->validate('CustomerNo');
    }

    public function getSessionBody()
    {
        $data = [
            'OrderNo'    => $this->getOrderNo(),
            'ReferNo'    => $this->getReferNo(),
            'CustomerNo' => $this->getCustomerNo(),
        ];
        return $data;
    }

    public function sendData($data)
    {
        $url = $this->getEndpoint();
        $body = $this->getRequestBody();

        $response = $this->httpClient->post($url)->setBody($body)->send()->getBody();

        return $this->response = new QueryOrderResponse($this, (string)$response);
    }
}
