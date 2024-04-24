<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @const NAMEPATTERN
 *  Patteren of name.
 */
const NAMEPATTERN = "/^[a-zA-Z ]+$/";

/**
 * @const NAMEPATTERN
 *  Patteren of name.
 */
const MARKSVALIDATE = "/^\d+%$/";
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
    private ?int $id = NULL;

    #[ORM\Column(length: 255)]

    /**
     * @var string $name.
     *  Store the name of the user.
     */
    #[Assert\Regex(
        pattern: NAMEPATTERN,
        match: TRUE,
        message: 'Your name cannot contain a number.',
    )]
    private ?string $name = NULL;

    #[ORM\Column(length: 255)]

    /**
     * @var string $schooling_percent.
     *  Store the school percentage of user.
     */
    #[Assert\Regex(
        pattern: MARKSVALIDATE,
        match: TRUE,
        message: 'Your name cannot contain a number.',
    )]
    private ?string $schooling_percent = NULL;

    #[ORM\Column(length: 255)]

    /**
     * @var string $graduation_percentage
     *  Store the graduation marks of the user in percent.
     */
    #[Assert\Regex(
        pattern: MARKSVALIDATE,
        match: TRUE,
        message: 'Your name cannot contain a number.',
    )]
    private ?string $graduation_percent = NULL;

    #[ORM\Column(length: 255)]

    /**
     * @var string resume_link
     *  Store the resume links.
     */
    private ?string $resume_link = NULL;

    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: FALSE)]
    private ?User $user = NULL;

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

    /**
     * Public function __construct()
     *  Create cunstructor();
     */
    public function __construct()
    {
        $this->exams = new ArrayCollection();
        $this->profileExamRelateds = new ArrayCollection();
    }

    /**
     * Function getId()
     *  To get the profileId.
     *
     * @return int id.
     *  Profile id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Function getName()
     *  To get the profileName.
     *
     * @return string name.
     *  Profile name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Function setName()
     *  To set the profileName.
     *
     * @param string name.
     *  Profile name.
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Function getSchoolingPercente()
     *  To get the Schooling percentage of user.
     *
     * @return string schoolingPercentage.
     *  Return the schoolign percentage of user in the form of %.
     */
    public function getSchoolingPercent(): ?string
    {
        return $this->schooling_percent;
    }

    /**
     * Function setSchoolingPercente()
     *  To set the Schooling percentage of user.
     *
     * @param string schoolingPercentage.
     *  Schoolign percentage of user in the form of %.
     */
    public function setSchoolingPercent(string $schooling_percent): static
    {
        $this->schooling_percent = $schooling_percent;

        return $this;
    }

    /**
     * Function getGraduationPercente()
     *  To get the Graduation percentage of user.
     *
     * @return string graduationPercentage.
     *  Return the graduation percentage of user in the form of %.
     */
    public function getGraduationPercent(): ?string
    {
        return $this->graduation_percent;
    }

    /**
     * Function setGraduationPercente()
     *  To set the Graduation percentage of user.
     *
     * @param string graduationPercentage.
     *  Graduation percentage of user in the form of %.
     */
    public function setGraduationPercent(string $graduation_percent): static
    {
        $this->graduation_percent = $graduation_percent;

        return $this;
    }

    /**
     * Function getResumeLink()
     *  To get the Resume link user.
     *
     * @return string resumeLink.
     *  Return the resumeLink.
     */
    public function getResumeLink(): ?string
    {
        return $this->resume_link;
    }

    /**
     * Function setResumeLink()
     *  To set the Resume link user.
     *
     * @param string resumeLink.
     *  The resumeLink.
     */
    public function setResumeLink(string $resume_link): static
    {
        $this->resume_link = $resume_link;

        return $this;
    }

    /**
     * Function getUSer()
     *  To get the user.
     *
     * @return User user
     *  Return the User from User Entity.
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Function setUSer()
     *  To set the user.
     *
     * @param User user
     *  The User from User Entity.
     */
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
            // set the owning side to NULL (unless already changed)
            if ($profileExamRelated->getProfile() === $this) {
                $profileExamRelated->setProfile(NULL);
            }
        }
        return $this;
    }
}
