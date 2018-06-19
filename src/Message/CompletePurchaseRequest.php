<?php

namespace Omnipay\YeePay\Message;


class CompletePurchaseRequest extends CodMsNotifyRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
       return parent::getData();
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return AopCompletePurchaseResponse
     */
    public function sendData($data)
    {
        $request = new CodMsNotifyRequest($this->httpClient, $this->httpRequest);
        $request->setkey($this->getKey());
        $data = $request->send()->getData();

        return $this->response = new CompletePurchaseResponse($this, $data);
    }

}
