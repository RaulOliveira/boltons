<?php


namespace App\Infrastructure\TaxInvoice\Exception;

use Exception;

class TaxInvoiceNotFoundException extends Exception
{
    public function __construct(string $accessKey = "", int $code = 404, \Throwable $previous = null)
    {
        parent::__construct('No record found with the key: '.$accessKey, $code, $previous);
    }
}