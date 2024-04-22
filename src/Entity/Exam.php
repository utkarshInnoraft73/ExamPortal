<?php

namespace App\Entity;

use App\Repository\ExamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ExamRepository::class)]
#[Broadcast]

/**
 * Class Exam.
 *  To manage the all functionality of exam entity.
 */
class Exam
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    /**
     * @var int $id
     *  Store the exam id in exam table.
     */
    private ?int $id = null;

    #[ORM\Column(length: 255)]

    /**
     * @var string $exam_name
     *  Store the exam sort name.
     */
    private ?string $exam_name = null;

    #[ORM\Column(length: 100)]

    /**
     * @var string $passing_marks
     *  Store the passing marks.
     */
    private ?string $passing_marks = null;

    /**
     * @var Collection<int, ProfileExamRelated>
     */
    #[ORM\OneToMany(targetEntity: ProfileExamRelated::class, mappedBy: 'exam')]
    private Collection $profileExamsRelatedExam;

    #[ORM\Column(length: 255)]

    /**
     * @var string $Created_by
     *  Store the Admin from whoes exams is created.
     */
    private ?string $Created_by = null;

    #[ORM\Column(length: 100)]

    /**
     * @var string $total_marks
     *  Store the total marks.
     */
    private ?string $total_marks = null;

    #[ORM\Column(length: 100)]

    /**
     * @var string $number_of_questios
     *  Store the .number of all questions.
     */
    private ?string $no_of_questios = null;

    #[ORM\Column(length: 255)]

    /**
     * @var string $duration
     *  Store the Exam durations.
     */
    private ?string $duration = null;

    /**
     * @var Collection<int, Questions>
     */
    #[ORM\OneToMany(targetEntity: Questions::class, mappedBy: 'exam', orphanRemoval: true)]
    private Collection $question;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]

    /**
     * @var string $exam_date
     *  Store the date of the exams.
     */
    private ?\DateTimeInterface $exam_date = null;

    #[ORM\Column(length: 255, nullable: true)]

    /**
     * @var string $required_schooling_marks.
     *  Store the required schooling marks of user.
     */
    private ?string $required_schooling_marks = null;

    #[ORM\Column(length: 255)]

    /**
     * @var string $reuired_graduation_marks
     *  Store the Reuired graduation marks.
     */
    private ?string $required_graduation_marks = null;

    /**
     * Public funtion to constructor.
     */
    public function __construct()
    {
        $this->profileExamsRelatedExam = new ArrayCollection();
        $this->question = new ArrayCollection();
    }

    /**
     * Public funtion getId()
     *  To get the exam Id.
     *
     * @return int examId.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Public funtion getExamName()
     *  To get the Exam name.
     *
     * @return string examName.
     */
    public function getExamName(): ?string
    {
        return $this->exam_name;
    }

    /**
     * Public funtion setExamName()
     *  To set the exam name.
     *
     * @param string examName.
     */
    public function setExamName(string $exam_name): static
    {
        $this->exam_name = $exam_name;

        return $this;
    }

    /**
     * Public funtion getPassingMarks()
     *  To get the exam passing marks.
     *
     * @return int examPassingMarks.
     */
    public function getPassingMarks(): ?string
    {
        return $this->passing_marks;
    }

    /**
     * Public funtion setPassingMarks()
     *  To set Passing marks.
     *
     * @param string $passing_marks.
     *  To set the passign marks for the exam passign marks.
     */
    public function setPassingMarks(string $passing_marks): static
    {
        $this->passing_marks = $passing_marks;

        return $this;
    }

    /**
     * @return Collection<int, ProfileExamRelated>
     */
    public function getProfileExamsRelatedExam(): Collection
    {
        return $this->profileExamsRelatedExam;
    }

    public function addProfileExamsRelatedExam(ProfileExamRelated $profileExamsRelatedExam): static
    {
        if (!$this->profileExamsRelatedExam->contains($profileExamsRelatedExam)) {
            $this->profileExamsRelatedExam->add($profileExamsRelatedExam);
            $profileExamsRelatedExam->setExam($this);
        }

        return $this;
    }
    public function removeProfileExamsRelatedExam(ProfileExamRelated $profileExamsRelatedExam): static
    {

        if ($this->profileExamsRelatedExam->removeElement($profileExamsRelatedExam)) {
            // set the owning side to null (unless already changed)
            if ($profileExamsRelatedExam->getExam() === $this) {
                $profileExamsRelatedExam->setExam(null);
            }
        }

        return $this;
    }

    /**
     * Public funtion getCreatedBy()
     *  To get the Owner of the exam.
     *
     * @return string createdBy.
     */
    public function getCreatedBy(): ?string
    {
        return $this->Created_by;
    }

    /**
     * Public funtion setCreatedBy()
     *  To set owner of the exam.
     *
     * @param string $created_by.
     *  Admin name who is created the exam.
     */
    public function setCreatedBy(string $Created_by): static
    {
        $this->Created_by = $Created_by;

        return $this;
    }


    /**
     * Public funtion getTotalMarks()
     *  To get the exam Total Marks.
     *
     * @return string totalMarks.
     */
    public function getTotalMarks(): ?string
    {
        return $this->total_marks;
    }

    /**
     * Public funtion setTotalMarks()
     *  To set total marks.
     *
     * @param string $total_marks.
     */
    public function setTotalMarks(string $total_marks): static
    {
        $this->total_marks = $total_marks;

        return $this;
    }

    /**
     * Public funtion getNoOfQuestios()
     *  To get the number of questions in a exam.
     *
     * @return string number_of_questions.
     */
    public function getNoOfQuestios(): ?string
    {
        return $this->no_of_questios;
    }

    /**
     * Public funtion setNoOfQuestios()
     *  To set the number of questions in a exam.
     *
     * @param string number_of_questions.
     */
    public function setNoOfQuestios(string $no_of_questios): static
    {
        $this->no_of_questios = $no_of_questios;

        return $this;
    }

    /**
     * Public funtion getDuration()
     *  To get the number of questions in a exam.
     *
     * @return string exam_duration.
     */
    public function getDuration(): ?string
    {
        return $this->duration;
    }

    /**
     * Public funtion setDuration()
     *  To set the duration of exam.
     *
     * @param string duration.
     */
    public function setDuration(string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection<int, Questions>
     */
    public function getQuestion(): Collection
    {
        return $this->question;
    }

    public function addQuestion(Questions $question): static
    {
        if (!$this->question->contains($question)) {
            $this->question->add($question);
            $question->setExam($this);
        }

        return $this;
    }

    public function removeQuestion(Questions $question): static
    {
        if ($this->question->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getExam() === $this) {
                $question->setExam(null);
            }
        }

        return $this;
    }

    /**
     * Public funtion getExamDate()
     *  To get the date of exam.
     *
     * @return string exam_date.
     */
    public function getExamDate(): ?\DateTimeInterface
    {
        return $this->exam_date;
    }

    /**
     * Public funtion setExamDate()
     *  To get the date of exam.
     *
     * @param DateTimeInterface exam_date.
     */
    public function setExamDate(?\DateTimeInterface $exam_date): static
    {
        $this->exam_date = $exam_date;

        return $this;
    }

    /**
     * Public funtion getRequiredSchoolingMarks()
     *  To get the required schooling marks.
     *
     * @return string required_schooling_marks.
     */
    public function getRequiredSchoolingMarks(): ?string
    {
        return $this->required_schooling_marks;
    }

    /**
     * Public funtion setRequiredSchoolingMarks()
     *  To set the required schooling marks.
     *
     * @param string required_schooling_marks.
     */
    public function setRequiredSchoolingMarks(?string $required_schooling_marks): static
    {
        $this->required_schooling_marks = $required_schooling_marks;

        return $this;
    }

    /**
     * Public funtion getRequiredGraduationMarks()
     *  To get the required graduation marks.
     *
     * @return string required_graduation_marks.
     */
    public function getRequiredGraduationMarks(): ?string
    {
        return $this->required_graduation_marks;
    }

    /**
     * Public funtion setRequiredGraduationMarks()
     *  To set the required graduation marks.
     *
     * @param string required_graduation_marks.
     */
    public function setRequiredGraduationMarks(string $required_graduation_marks): static
    {
        $this->required_graduation_marks = $required_graduation_marks;

        return $this;
    }
}
