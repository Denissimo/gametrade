<?php

namespace App\Entity;

use App\Repository\TarifRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=TarifRepository::class)
 */
class Tarif
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfAccounts;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceAccount;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceChangeAccount;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNumberOfAccounts(): ?int
    {
        return $this->numberOfAccounts;
    }

    public function setNumberOfAccounts(int $numberOfAccounts): self
    {
        $this->numberOfAccounts = $numberOfAccounts;

        return $this;
    }

    public function getPriceAccount(): ?int
    {
        return $this->priceAccount;
    }

    public function setPriceAccount(int $priceAccount): self
    {
        $this->priceAccount = $priceAccount;

        return $this;
    }

    public function getPriceChangeAccount(): ?int
    {
        return $this->priceChangeAccount;
    }

    public function setPriceChangeAccount(int $priceChangeAccount): self
    {
        $this->priceChangeAccount = $priceChangeAccount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
