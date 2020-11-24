<?php

namespace App\Infrastructure\TaxInvoice\Http\Api\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Domain\TaxInvoice\Repository\TaxInvoiceRepositoryInterface;
use App\Infrastructure\TaxInvoice\Exception\TaxInvoiceNotFoundException;

class TaxInvoiceController extends AbstractController
{
    private TaxInvoiceRepositoryInterface $taxInvoiceRepository;

    public function __construct(TaxInvoiceRepositoryInterface $taxInvoiceRepository)
    {
        $this->taxInvoiceRepository = $taxInvoiceRepository;
    }
    public function getTaxInvoice(string $accessKey) 
    {
        try {
            $taxInvoice = $this->taxInvoiceRepository->findByAccessKey($accessKey);
            $status     = 200;
            $response   = [
                'accessKey'  => $taxInvoice->getAccessKey(),
                'totalValue' => $taxInvoice->getTotalValue()
            ];
        } catch(TaxInvoiceNotFoundException $error) {
            $status     = 404;
            $response   = ['error' => $error->getMessage()];
        } catch(\Exception $error) {
            $status     = 500;
            $response   = ['error' => 'Oops! Something strange happened.'];
        }

        return (new JsonResponse())
            ->setStatusCode($status)
            ->setContent(json_encode($response));
    }
}