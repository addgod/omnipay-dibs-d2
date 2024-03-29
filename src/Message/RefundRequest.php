<?php

namespace Omnipay\DibsD2\Message;

class RefundRequest extends GeneralRequest
{
    public $endpoint = 'https://%s:%s@payment.architrade.com/cgi-adm/refund.cgi';

    public function getData()
    {
        $data = [
            'merchant'      => $this->getMerchantId(),
            'transact'      => $this->getTransactionReference(),
            'amount'        => $this->getAmountInteger(),
            'currency'      => $this->getCurrencyNumeric(),
            'orderid'       => $this->getTransactionId(),
            'md5key'        => $this->getMd5Key(),
            'textreply'     => "yes",
        ];

        return $data;
    }

    public function sendData($data)
    {
        $endpoint = sprintf($this->endpoint, $this->getUsername(), $this->getPassword());
        $http_response = $this->httpClient->request('POST', $endpoint, ['Content-type' => 'text/plain'], http_build_query($data));
        parse_str($http_response->getBody(true), $output);
        return $this->response = new PostResponse($this, $output);
    }
}
