<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
/**
 * Class Profile.
 *  All Functionality and features related to the profile table.
 */
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    /**
     * @var int $id
     *  The profile id.
     */
    private ?int $id = null;

    #[ORM\Column(length: 255)]

    /**
     * @var string $name.
     *  Store the name of the user.
     */
    private ?string $name = null;

    #[ORM\Column(length: 255)]

    /**
     * @var string $schooling_percent.
     *  Store the school percentage of user.
     */
    private ?string $schooling_percent = null;

    #[ORM\Column(length: 255)]

    /**
     * @var string $graduation_percentage
     *  Store the graduation marks of the user in percent.
     */
    private ?string $graduation_percent = null;

    #[ORM\Column(length: 255)]

    /**
     *
     */
    private ?string $resume_link = null;

    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Exam>
     */
    #[ORM\ManyToMany(targetEntity: Exam::class, inversedBy: 'profiles')]
    private Collection $exams;

    /**
     * @var Collection<int, ProfileExamRelated>
     */
    #[ORM\OneToMany(targetEntity: ProfileExamRelated::class, mappedBy: 'profile')]
    private Collection $profileExamRelateds;

    public function __construct()
    {
        $this->exams = new ArrayCollection();
        $this->profileExamRelateds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSchoolingPercent(): ?string
    {
        return $this->schooling_percent;
    }

    public function setSchoolingPercent(string $schooling_percent): static
    {
        $this->schooling_percent = $schooling_percent;

        return $this;
    }

    public function getGraduationPercent(): ?string
    {
        return $this->graduation_percent;
    }

    public function setGraduationPercent(string $graduation_percent): static
    {
        $this->graduation_percent = $graduation_percent;

        return $this;
    }

    public function getResumeLink(): ?string
    {
        return $this->resume_link;
    }

    public function setResumeLink(string $resume_link): static
    {
        $this->resume_link = $resume_link;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Exam>
     */
    public function getExams(): Collection
    {
        return $this->exams;
    }

    public function addExam(Exam $exam): static
    {
        if (!$this->exams->contains($exam)) {
            $this->exams->add($exam);
        }

        return $this;
    }

    public function removeExam(Exam $exam): static
    {
        $this->exams->removeElement($exam);

        return $this;
    }

    /**
     * @return Collection<int, ProfileExamRelated>
     */
    public function getProfileExamRelateds(): Collection
    {
        return $this->profileExamRelateds;
    }

    public function addProfileExamRelated(ProfileExamRelated $profileExamRelated): static
    {
        if (!$this->profileExamRelateds->contains($profileExamRelated)) {
            $this->profileExamRelateds->add($profileExamRelated);
            $profileExamRelated->setProfile($this);
        }

        return $this;
    }

    public function removeProfileExamRelated(ProfileExamRelated $profileExamRelated): static
    {
        if ($this->profileExamRelateds->removeElement($profileExamRelated)) {
            // set the owning side to null (unless already changed)
            if ($profileExamRelated->getProfile() === $this) {
                $profileExamRelated->setProfile(null);
            }
        }
        return $this;
    }
}
