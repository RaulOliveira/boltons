<?php 

namespace App\Infrastructure\Arquivei\Service;

use Symfony\Component\HttpClient\HttpClient;
use App\Domain\TaxInvoice\Entity\TaxInvoice;
use App\Domain\Arquivei\Service\NfeServiceInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Domain\TaxInvoice\Repository\TaxInvoiceRepositoryInterface;
use App\Infrastructure\TaxInvoice\Exception\TaxInvoiceNotFoundException;

class NfeService implements NfeServiceInterface 
{
    private string $endpoint = 'https://sandbox-api.arquivei.com.br/v1/nfe/received';
    private HttpClientInterface $client;
    private TaxInvoiceRepositoryInterface $taxInvoiceRepository;

    public function __construct(
        string $apiId, 
        string $apiKey,
        TaxInvoiceRepositoryInterface $taxInvoiceRepository
    ) {
        $this->taxInvoiceRepository = $taxInvoiceRepository;
        $this->client = HttpClient::create([
            'headers' => [
                'Content-Type' => 'application/json',
                'X-API-ID' => $apiId,
                'X-API-KEY' => $apiKey,
            ]
        ]);
    }

    public function sync()
    {
        $allTaxInvoices = [];
        $endpoint    = $this->endpoint;
        do {
            $response = $this->client
                            ->request('GET', $endpoint)
                            ->toArray()
                        ;

            $invoices       = $this->process($response);
            $allTaxInvoices = array_merge($allTaxInvoices, $invoices);

            $count    = count($invoices);
            $endpoint = $response['page']['next'] ?? $this->endpoint;            
        } while ($count > 0);

        foreach ($allTaxInvoices as $taxInvoice) {
            $taxInvoice = $this->buildTaxInvoice($taxInvoice);
            $this->taxInvoiceRepository->save($taxInvoice);
        }

        return $allTaxInvoices;
    }

    private function buildTaxInvoice(TaxInvoice $taxInvoice)
    {
        try {
            $invoiceSaved = $this->taxInvoiceRepository->findByAccessKey($taxInvoice->getAccessKey());
            $invoiceSaved->updateValue($taxInvoice->getTotalValue());
            return $invoiceSaved;
        } catch(TaxInvoiceNotFoundException $error) {
            return $taxInvoice;
        }
    }

    private function process(array $response)
    {   
        $data = $response['data'] ?? [];
        return array_map(function (array $data) {
            return new TaxInvoice(
                $data['access_key'],
                $this->getTotalValue($data['xml'])
            );
        }, $data);
    }

    public function getTotalValue(string $xmlEncoded)
    {        
        $xml = simplexml_load_string(base64_decode($xmlEncoded));
        $elementRoot = $xml->NFe->infNFe ?? $xml->infNFe;
        return (float) $elementRoot
                           ->total
                           ->ICMSTot
                           ->vNF
                        ;
    }

}