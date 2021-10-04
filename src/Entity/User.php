<?php

// src/Entity/User.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User extends BaseUser implements UserInterface
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    public const ROLE_SONATA_PAGE_ADMIN_PAGE_EDIT = 'ROLE_SONATA_PAGE_ADMIN_PAGE_EDIT';
    public const ROLE_OPERATOR = 'ROLE_OPERATOR';
    public const ROLE_MANAGER = 'ROLE_MANAGER';
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $middlename;

    /**
     * @ORM\OneToMany(targetEntity=Account::class, mappedBy="owner")
     */
    private $accounts;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="operator")
     */
    private $tasks;

    /**
     * @ORM\OneToOne(targetEntity=FinanceAccount::class, mappedBy="owner", cascade={"persist", "remove"})
     */
    private $financeAccount;


    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->accounts = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }


    public function getMiddlename(): ?string
    {
        return $this->middlename;
    }

    public function setMiddlename(?string $middlename): self
    {
        $this->middlename = $middlename;

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->setOwner($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->accounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getOwner() === $this) {
                $account->setOwner(null);
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
            $task->setOperator($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getOperator() === $this) {
                $task->setOperator(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getFioUsername();
    }

    public function getFioUsername(): string
    {
        return sprintf(
            '%s %s (%s)',
            $this->firstname,
            $this->lastname,
            $this->username
        );
    }

    public function hasRole($role)
    {
        return parent::hasRole($role);
    }

    public function getFinanceAccount(): ?FinanceAccount
    {
        return $this->financeAccount;
    }

    public function setFinanceAccount(FinanceAccount $financeAccount): self
    {
        // set the owning side of the relation if necessary
        if ($financeAccount->getOwner() !== $this) {
            $financeAccount->setOwner($this);
        }

        $this->financeAccount = $financeAccount;

        return $this;
    }
}