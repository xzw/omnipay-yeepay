<?php
/**
 * Created by PhpStorm.
 * User: Master
 * Date: 2018/3/22
 * Time: 15:04
 */

namespace Omnipay\YeePay\Message;


use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\YeePay\Helper;
use Symfony\Component\HttpFoundation\ParameterBag;

class CodMsNotifyRequest extends BaseAbstractRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        return file_get_contents('php://input');
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        return $this->response = new CodMsNotifyResponse($this, $data);
    }


    public function getServiceCode()
    {
        $data = $this->getData();

        return array_get($data, 'SessionHead.ServiceCode');
    }

    public function setServiceCode($serviceCode)
    {
        $this->setParameter('ServiceCode', $serviceCode);
    }

}