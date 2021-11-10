<?php

namespace Omnipay\DibsD2\Message;

class CaptureRequest extends GeneralRequest
{
    public $endpoint = 'https://payment.architrade.com/cgi-bin/capture.cgi';

    public function getData()
    {
        $data = [
            'merchant'      => $this->getMerchantId(),
            'amount'        => $this->getAmountInteger(),
            'transact'      => $this->getTransactionReference(),
            'orderid'       => $this->getTransactionId(),
        ];

        return $data;
    }

    public function sendData($data)
    {
        $http_response = $this->httpClient->request('POST', $this->endpoint, ['Content-type' => 'text/plain'], http_build_query($data));
        parse_str($http_response->getBody(true), $output);
        return $this->response = new PostResponse($this, $output);
    }
}
