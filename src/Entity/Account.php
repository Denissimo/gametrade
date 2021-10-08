<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 */
class Account
{
    public const STATUS_NEW = 0; //Новый
    public const STATUS_RESERVED = 1; //Зарезервирован
    public const STATUS_IN_WORK = 2; //В реботе
    public const STATUS_DONE = 3; //Готов
    public const STATUS_SOLD = 4; //Продан
    public const STATUS_FROZEN = 5; //Заморожен

    public static $statuses = [
        self::STATUS_NEW => 'Новый',
        self::STATUS_RESERVED => 'Зарезервирован',
        self::STATUS_IN_WORK => 'В работе',
        self::STATUS_DONE => 'Готов',
        self::STATUS_SOLD => 'Продан',
        self::STATUS_FROZEN => 'Заморожен'
    ];

    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="accounts")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $operator;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="accounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $externalId;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

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

    /**
     * @ORM\OneToMany(targetEntity=Credential::class, mappedBy="account", orphanRemoval=true)
     */
    private $credentials;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="account")
     */
    private $tasks;

    /**
     * @ORM\ManyToOne(targetEntity=Task::class, inversedBy="accounts")
     */
    private $task;

    /**
     * @ORM\ManyToOne(targetEntity=Basket::class, inversedBy="account")
     */
    private $basket;

    public function __construct()
    {
        $this->credentials = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     */
    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function getStringId(): string
    {
        return (string) $this->id;
    }


    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getOperator(): ?User
    {
        return $this->operator;
    }

    public function setOperator(?User $operator): self
    {
        $this->operator = $operator;

        return $this;
    }


    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

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

    /**
     * @return Collection|Credential[]
     */
    public function getCredentials(): Collection
    {
        return $this->credentials;
    }

    public function addCredential(Credential $credential): self
    {
        if (!$this->credentials->contains($credential)) {
            $this->credentials[] = $credential;
            $credential->setAccount($this);
        }

        return $this;
    }

    public function removeCredential(Credential $credential): self
    {
        if ($this->credentials->removeElement($credential)) {
            // set the owning side to null (unless already changed)
            if ($credential->getAccount() === $this) {
                $credential->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setAccount($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getAccount() === $this) {
                $task->setAccount(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id->toString();
    }

    public function getStatusName()
    {
        return self::$statuses[$this->status];
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getBasket(): ?Basket
    {
        return $this->basket;
    }

    public function setBasket(?Basket $basket): self
    {
        $this->basket = $basket;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
