<?php
/**
 * Created by PhpStorm.
 * User: Master
 * Date: 2018/3/19
 * Time: 17:46
 */

namespace Omnipay\YeePay;


class PosGateway extends PosAbstractGateway
{

    protected $endpoints = [
        'production' => 'http://cod.yeepay.com/cod/callback/codOrderQueryAction.do',
        'sandbox'    => 'http://203.81.247.126:8081/cod/callback/codOrderQueryAction.do',
    ];
    
    public function getName()
    {
        return 'YeePay Pos';
    }


}