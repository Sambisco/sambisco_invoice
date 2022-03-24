<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    
    #[ORM\Column(type: 'integer')]
    private $number;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'invoices')]
    private $customerId;

    #[ORM\Column(type: 'date')]
    private $date;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: DetaiInvoice::class)]
    private $detaiInvoices;

    
    public function __construct()
    {
        $this->details = new ArrayCollection();
        $this->detaiInvoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

   

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getCustomerId(): ?Customer
    {
        return $this->customerId;
    }

    public function setCustomerId(?Customer $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Details>
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    /**
     * @return Collection<int, DetaiInvoice>
     */
    public function getDetaiInvoices(): Collection
    {
        return $this->detaiInvoices;
    }

    public function addDetaiInvoice(DetaiInvoice $detaiInvoice): self
    {
        if (!$this->detaiInvoices->contains($detaiInvoice)) {
            $this->detaiInvoices[] = $detaiInvoice;
            $detaiInvoice->setInvoice($this);
        }

        return $this;
    }

    public function removeDetaiInvoice(DetaiInvoice $detaiInvoice): self
    {
        if ($this->detaiInvoices->removeElement($detaiInvoice)) {
            // set the owning side to null (unless already changed)
            if ($detaiInvoice->getInvoice() === $this) {
                $detaiInvoice->setInvoice(null);
            }
        }

        return $this;
    }

    

    
}
