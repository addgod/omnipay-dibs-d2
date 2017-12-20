<?php
/**
 * Created by PhpStorm.
 * User: jkh
 * Date: 12/19/17
 * Time: 2:46 PM
 */

namespace Omnipay\DibsD2\Message;

use Omnipay\Common\Message\AbstractRequest;


abstract class GeneralRequest extends AbstractRequest
{
    public function getData()
    {
        $card = $this->getCard();

        $data = [
            'accepturl'             => $this->getReturnUrl(),
            'amount'                => $this->getAmountInteger(),
            'callbackurl'           => $this->getCallbackUrl(),
            'currency'              => $this->getCurrency(),
            'merchant'              => $this->getMerchantId(),
            'orderid'               => $this->getOrderId(),
            'md5key'                => $this->getMd5Key(),
            'lang'                  => $this->getLang(),
            'test'                  => $this->getTestMode(),
        ];

        if ($this->getCardReference()) {
            $data = array_merge($data, [
                'billingAddress'        => $card->getBillingAddress1(),
                'billingAddress2'       => $card->getBillingAddress2(),
                'billingFirstName'      => $card->getFirstName(),
                'billingLastName'       => $card->getLastName(),
                'billingPostalCode'     => $card->getBillingPostcode(),
                'billingPostalPlace'    => $card->getBillingCity(),
                'email'                 => $card->getEmail(),
            ]);
        }

        return array_filter($data);
    }

    public function setCurrency($value)
    {
        if (preg_match('/^[0-9]{3}$/',$value)) {
            return parent::setCurrency($value);
        } else {
            throw new \UnexpectedValueException('Currency must be 3 digits, representing a currency. See https://en.wikipedia.org/wiki/ISO_4217');
        }

    }

    public function setLang($value)
    {
        return $this->setParameter('lang', $value);
    }

    public function getLang()
    {
        return $this->getParameter('lang');
    }

    public function setCallbackUrl($value)
    {
        return $this->setParameter('callbackUrl', $value);
    }

    public function getCallbackUrl()
    {
        return $this->getParameter('callbackUrl');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    protected function getMd5Key()
    {
        $key1 = $this->getKey1();
        $key2 = $this->getKey2();

        if (empty($key1) || empty($key2)) {
            return null;
        }

        $parameter_string = '';
        $parameter_string .= 'merchant=' . $this->getMerchantId();
        $parameter_string .= '&orderid=' . $this->getOrderId();
        $parameter_string .= '&currency=' . $this->getCurrency();
        $parameter_string .= '&amount=' . $this->getAmountInteger();

        return md5($key2 . md5($key1 . $parameter_string) );
    }

    public function setKey1($value)
    {
        return $this->setParameter('key1', $value);
    }

    public function getKey1()
    {
        return $this->getParameter('key1');
    }

    public function setKey2($value)
    {
        return $this->setParameter('key2', $value);
    }

    public function getKey2()
    {
        return $this->getParameter('key2');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

}