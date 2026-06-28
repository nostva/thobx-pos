<?php

namespace App\Controllers;

class Qz extends BaseController
{
    public function cert()
    {
        return $this->response
            ->setHeader('Content-Type', 'text/plain')
            ->setBody(file_get_contents(WRITEPATH . 'qz/digital-certificate.txt'));
    }

    public function sign()
    {
        $toSign = $this->request->getGet('request');
        $privateKey = file_get_contents(WRITEPATH . 'qz/private-key.pem');
        openssl_sign($toSign, $signature, $privateKey, OPENSSL_ALGO_SHA512);

        return $this->response
            ->setHeader('Content-Type', 'text/plain')
            ->setBody(base64_encode($signature));
    }
}