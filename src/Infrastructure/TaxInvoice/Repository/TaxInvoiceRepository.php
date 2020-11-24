<?php

namespace App\Infrastructure\TaxInvoice\Repository;

use Doctrine\ORM\EntityManagerInterface;
use App\Domain\TaxInvoice\Entity\TaxInvoice;
use App\Domain\TaxInvoice\Repository\TaxInvoiceRepositoryInterface;
use App\Infrastructure\TaxInvoice\Exception\TaxInvoiceNotFoundException;

Class TaxInvoiceRepository implements TaxInvoiceRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByAccessKey(string $accessKey): ?TaxInvoice
    {
        $taxInvoice = $this->entityManager
            ->getRepository(TaxInvoice::class)
            ->findOneBy([
                'accessKey' => $accessKey
            ])
        ;

        if (!$taxInvoice instanceof TaxInvoice) {
            throw new TaxInvoiceNotFoundException($accessKey);
        }
        
        return $taxInvoice;
    }

    public function save(TaxInvoice $taxInvoice): void
    {
        if (is_null($taxInvoice->getId())) {
            $this->entityManager->persist($taxInvoice);
        }
        $this->entityManager->flush();
    }
}