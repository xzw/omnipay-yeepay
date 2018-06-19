<?php

namespace Omnipay\YeePay;

class Helper
{

    public static function array2xml($arr, $root = '')
    {
        if (empty($root))
            $root = '<?xml version="1.0" encoding="UTF-8"?><COD-MS></COD-MS>';

        $xml = new \SimpleXMLElement($root);
        self::array_to_xml($arr, $xml);

        return $xml->asXML();
    }

    static function array_to_xml($arr, &$xml_data)
    {
        foreach ($arr as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item' . $key; //dealing with <0/>..<n/> issues
            }
            if (is_array($value)) {
                $subnode = $xml_data->addChild($key);
                self::array_to_xml($value, $subnode);
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    public static function xml2array($xml)
    {
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }


    public static function sign($xml, $key)
    {
        $xmlNodeStartString = '<?xml version="1.0" encoding="UTF-8"?><COD-MS>';
        $xmlNodeEndString = '</COD-MS>';

        // 需要加密的字符串
        $session = "";

        if (preg_match("#<COD-MS>(?<session>.*)</COD-MS>#m", $xml, $matches)) {
            $session = $matches['session'];
        }

        $session = preg_replace('#<HMAC>.*</HMAC>#', '', $session);

        $session = trim($session);

        $md5 = md5($session . $key);

        $part = explode('</SessionHead>', $session);

        $startStr = current($part);
        $endStr = next($part);

        return $xmlNodeStartString . $startStr . '<HMAC>' . $md5 . '</HMAC>' . '</SessionHead>' . $endStr . $xmlNodeEndString;
    }

    public static function verify($xml, $key)
    {
        // 需要加密的字符串
        $session = "";
        // 本次传送xml的md5
        $hmac = "";

        if (preg_match("#<COD-MS>(?<session>.*)</COD-MS>#m", $xml, $matches)) {
            $session = $matches['session'];
        }

        if (preg_match("#<HMAC>(?<hmac>.{32}).*</HMAC>#m", $xml, $matches)) {
            $hmac = $matches['hmac'];
        }

        $session = preg_replace('#<HMAC>.*</HMAC>#', '', $session);

        $session = trim($session);

        $md5 = md5($session . $key);

        return $md5 == $hmac;
    }
}
