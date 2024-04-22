<?php

namespace App\Entity;

use App\Repository\ProfileExamRelatedRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ProfileExamRelatedRepository::class)]
#[Broadcast]

/**
 * Class ProfileExamRelated.
 * To manage the entity profileExamRelated .
 *
 * Basically this class manage the which profile is related to the which exams.
 */
class ProfileExamRelated
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    /**
     * @var int id.
     *  Id.
     */
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'profileExamRelateds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $profile = null;

    #[ORM\ManyToOne(inversedBy: 'profileExamsRelatedExam')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exam $exam = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Public function getProfile()
     *  To get the profile.
     *
     * @return Profile profile.
     */
    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    /**
     * Public function setProfile()
     *  To set the profile.
     *
     * @param Profile profile.
     */
    public function setProfile(?Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Public function getExam()
     *  To get the exam.
     *
     * @return Exam exam.
     */
    public function getExam(): ?Exam
    {
        return $this->exam;
    }

    /**
     * Public function setExam()
     *  To set the Exam.
     *
     * @param Exam exam.
     */
    public function setExam(?Exam $exam): static
    {
        $this->exam = $exam;

        return $this;
    }
}
