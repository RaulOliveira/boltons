<?php

namespace App\Domain\TaxInvoice\Repository;

use App\Domain\TaxInvoice\Entity\TaxInvoice;

interface TaxInvoiceRepositoryInterface
{
    public function findByAccessKey(string $accessKey): ?TaxInvoice;
    public function save(TaxInvoice $taxInvoice): void;
}