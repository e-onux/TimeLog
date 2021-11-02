<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
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
    private $project_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $project_description;

    /**
     * @ORM\OneToMany(targetEntity=TimeLog::class, mappedBy="project")
     */
    private $timeLogs;

    public function __construct()
    {
        $this->timeLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectName(): ?string
    {
        return $this->project_name;
    }

    public function setProjectName(string $project_name): self
    {
        $this->project_name = $project_name;

        return $this;
    }

    public function getProjectDescription(): ?string
    {
        return $this->project_description;
    }

    public function setProjectDescription(?string $project_description): self
    {
        $this->project_description = $project_description;

        return $this;
    }

    /**
     * @return Collection|TimeLog[]
     */
    public function getTimeLogs(): Collection
    {
        return $this->timeLogs;
    }

    public function addTimeLog(TimeLog $timeLog): self
    {
        if (!$this->timeLogs->contains($timeLog)) {
            $this->timeLogs[] = $timeLog;
            $timeLog->setProject($this);
        }

        return $this;
    }

    public function removeTimeLog(TimeLog $timeLog): self
    {
        if ($this->timeLogs->removeElement($timeLog)) {
            // set the owning side to null (unless already changed)
            if ($timeLog->getProject() === $this) {
                $timeLog->setProject(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->project_name;
    }
}
