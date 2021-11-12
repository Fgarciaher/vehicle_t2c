<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VehicleRepository::class)
 */
class Vehicle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $license;

    /**
     * @ORM\ManyToOne(targetEntity=Model::class, inversedBy="vehicles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $model;

    /**
     * @ORM\Column(type="datetime")
     */
    private $entry_date;

    /**
     * @ORM\Column(type="float")
     */
    private $cost;

    /**
     * @ORM\ManyToOne(targetEntity=Store::class, inversedBy="vehicles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $store;

    /**
     * @ORM\OneToMany(targetEntity=Sale::class, mappedBy="vehicle")
     */
    private $sales;

    private $is_sold;
    private $last_sale_date;

    public function __construct()
    {
        $this->sales = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLicense(): ?string
    {
        return $this->license;
    }

    public function setLicense(?string $license): self
    {
        $this->license = $license;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getEntryDate(): ?\DateTimeInterface
    {
        return $this->entry_date;
    }

    public function setEntryDate(\DateTimeInterface $entry_date): self
    {
        $this->entry_date = $entry_date;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): self
    {
        $this->store = $store;

        return $this;
    }

    public function getIsSold(): ?bool
    {
        return $this->is_sold = count($this->getSales()) > 0;
    }

    public function setIsSold(?bool $is_sold): self
    {
        $this->is_sold = $is_sold;

        return $this;
    }

    public function getLastSaleDate(): ?\DateTimeInterface
    {
        foreach ($this->sales as $sale){
            $this->last_sale_date = $sale->getSaleDate();
        }
        return $this->last_sale_date;
    }

    public function setLastSaleDate(\DateTimeInterface $last_sale_date): self
    {
        $this->last_sale_date = $last_sale_date;
        return $this;
    }

    /**
     * @return Collection|Sale[]
     */
    public function getSales(): Collection
    {
        return $this->sales;
    }

    public function addSale(Sale $sale): self
    {
        if (!$this->sales->contains($sale)) {
            $this->sales[] = $sale;
            $sale->setVehicle($this);
        }

        return $this;
    }

    public function removeSale(Sale $sale): self
    {
        if ($this->sales->removeElement($sale)) {
            // set the owning side to null (unless already changed)
            if ($sale->getVehicle() === $this) {
                $sale->setVehicle(null);
            }
        }

        return $this;
    }
}
