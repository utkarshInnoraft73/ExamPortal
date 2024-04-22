<?php

namespace App\Entity;

use App\Repository\SolutionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: SolutionRepository::class)]
#[Broadcast]
class Solution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $ques_id = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $user_ans = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $correct_ans = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $marks = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuesId(): ?array
    {
        return $this->ques_id;
    }

    public function setQuesId(?array $ques_id): static
    {
        $this->ques_id = $ques_id;

        return $this;
    }

    public function getUserAns(): ?array
    {
        return $this->user_ans;
    }

    public function setUserAns(?array $user_ans): static
    {
        $this->user_ans = $user_ans;

        return $this;
    }

    public function getCorrectAns(): ?array
    {
        return $this->correct_ans;
    }

    public function setCorrectAns(?array $correct_ans): static
    {
        $this->correct_ans = $correct_ans;

        return $this;
    }

    public function getMarks(): ?array
    {
        return $this->marks;
    }

    public function setMarks(?array $marks): static
    {
        $this->marks = $marks;

        return $this;
    }
}
