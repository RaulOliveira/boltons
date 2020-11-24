<?php

namespace App\Domain\TaxInvoice\Entity;

class TaxInvoice 
{
    private ?int $id;
    private string $accessKey;
    private float $totalValue;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function __construct(string $accessKey, float $totalValue)
    {
        $this->accessKey  = $accessKey;
        $this->totalValue = $totalValue;
    }

    /**
     * Get the value of id
    */ 
    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    /**
     * Get the value of accessKey
     */ 
    public function getAccessKey()
    {
        return $this->accessKey;
    }

    /**
     * Get the value of totalValue
     */ 
    public function getTotalValue()
    {
        return $this->totalValue;
    }

    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get the value of updatedAt
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    public function updateValue(float $totalValue)
    {
        $this->totalValue = $totalValue;
    }
}
