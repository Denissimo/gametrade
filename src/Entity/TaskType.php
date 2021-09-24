<?php

namespace App\Entity;

use App\Repository\TaskTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskTypeRepository::class)
 */
class TaskType
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
    private $award;

    /**
     * @ORM\Column(type="integer")
     */
    private $overduePenalty;

    /**
     * @ORM\Column(type="integer")
     */
    private $rejectPenalty;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $defaultDuration;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="type", orphanRemoval=true)
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

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

    public function getAward(): ?int
    {
        return $this->award;
    }

    public function setAward(int $award): self
    {
        $this->award = $award;

        return $this;
    }

    public function getOverduePenalty(): ?int
    {
        return $this->overduePenalty;
    }

    public function setOverduePenalty(int $overduePenalty): self
    {
        $this->overduePenalty = $overduePenalty;

        return $this;
    }

    public function getRejectPenalty(): ?int
    {
        return $this->rejectPenalty;
    }

    public function setRejectPenalty(int $rejectPenalty): self
    {
        $this->rejectPenalty = $rejectPenalty;

        return $this;
    }

    public function getDefaultDuration(): ?int
    {
        return $this->defaultDuration;
    }

    public function setDefaultDuration(?int $defaultDuration): self
    {
        $this->defaultDuration = $defaultDuration;

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
            $task->setType($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getType() === $this) {
                $task->setType(null);
            }
        }

        return $this;
    }
}
