<?php

namespace App\Entity;

use App\Repository\TimeLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TimeLogRepository::class)
 */
class TimeLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $work_day;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start_time;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end_time;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="timeLogs")
     */
    private $project;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkDay(): ?\DateTimeInterface
    {
        return $this->work_day;
    }

    public function setWorkDay(\DateTimeInterface $work_day): self
    {
        $this->work_day = $work_day;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(?\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->end_time;
    }

    public function setEndTime(?\DateTimeInterface $end_time): self
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}
