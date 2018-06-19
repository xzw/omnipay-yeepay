<?php
/**
 * Created by PhpStorm.
 * User: Master
 * Date: 2018/4/19
 * Time: 15:14
 */

namespace Omnipay\YeePay\Message;


class QueryOrderResponse extends BaseAbstractResponse
{
    public function isSuccessful()
    {
        return 'ok';
    }

}