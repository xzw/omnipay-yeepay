<?php
/**
 * Created by PhpStorm.
 * User: Master
 * Date: 2018/3/23
 * Time: 10:35
 */

namespace Omnipay\YeePay\Message;


use Omnipay\YeePay\Helper;

class CodMsNotifyResponse extends BaseAbstractResponse
{

    public function isSuccessful()
    {
        // TODO: Implement isSuccessful() method.
    }

    public function getSessionHead()
    {
        $data = $this->getData();

        $default = [
            'Version'       => array_get($data, 'SessionHead.Version', '1.0'),
            'ServiceCode'   => array_get($data, 'SessionHead.ServiceCode'),
            'TransactionID' => array_get($data, 'SessionHead.TransactionID'),
            'SrcSysID'      => array_get($data, 'SessionHead.SrcSysID'),
            'DstSysID'      => array_get($data, 'SessionHead.DstSysID'),
            'RespTime'      => $this->getRespTime(),
            'ExtendAtt'     => '',
            'ResultCode'    => empty($this->getResultCode()) ? 2 : $this->getResultCode(),
            'ResultMsg'     => empty($this->getResultMsg()) ? '成功' : $this->getResultMsg(),
        ];

        return array_replace($default, $this->sessionHead);
    }

    public function setResultCode($value)
    {
        $this->parameters->set('ResultCode', $value);

        return $this;
    }

    public function getResultCode()
    {
        return $this->parameters->get('ResultCode');
    }

    public function setResultMsg($value)
    {
        $this->parameters->set('ResultMsg', $value);

        return $this;
    }

    public function getResultMsg()
    {
        return $this->parameters->get('ResultMsg');
    }

    public function setRespTime($time)
    {
        $this->setParameter('RespTime', $time);

        return $this;
    }


    public function toXml($root = '')
    {
        $data = $this->getSession();

        $xml = Helper::array2xml($data, $root);

        $key = $this->getKey();

        return Helper::sign($xml, $key);
    }

    public function __toString()
    {
        return $this->toXml();
    }

}