<?php
namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: QuestionsRepository::class)]
#[Broadcast]
class Questions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    /**
     * @var int id
     *  Stores the question id.
     */
    private ?int $id = null;

    #[ORM\Column(length: 255)]

    /**
     * @var string $question
     *  Stored the question title.
     */
    private ?string $question = null;

    #[ORM\Column(length: 255)]

    /**
     * @var string $opt_a
     *  Store the option one of question.
     */
    private ?string $opt_a = null;

    #[ORM\Column(length: 255)]

    /**
     * @var string $opt_b
     *  Store the option two of question.
     */
    private ?string $opt_b = null;

    #[ORM\Column(length: 255)]

    /**
     * @var string $opt_c
     *  Store the option three of question.
     */
    private ?string $opt_c = null;

    #[ORM\Column(length: 255)]

    /**
     * @var string $opt_d
     *  Store the option last of question.
     */
    private ?string $opt_d = null;

    #[ORM\Column(length: 255)]

    /**
     * @var string $correct_opt
     *  Store correct option of question.
     */
    private ?string $correct_opt = null;

    #[ORM\Column(length: 255)]

    /**
     * @var string
     */
    private ?string $marks_for_question = null;

    #[ORM\ManyToOne(inversedBy: 'question')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exam $exam = null;


    public function __construct()
    {
        // $this->examQuestions = new ArrayCollection();
    }

    /**
     * Public funtion getId()
     *  To get the question id
     *
     * @return int id.
     *  Return the question id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Public funtion getQuestion()
     *  To get the question Name
     *
     * @return string question.
     *  Return the question Title.
     */
    public function getQuestion(): ?string
    {
        return $this->question;
    }

    /**
     * Public funtion setQuestion()
     *  To set the question
     *
     * @param string question.
     *  The question title.
     */
    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Public function getOptA()
     *  To get the first option of question.
     *
     * @return string  opt_a
     *  Return the first option.
     */
    public function getOptA(): ?string
    {
        return $this->opt_a;
    }

    /**
     * Public function setOptA()
     *  To set the first option.
     *
     * @param string $opt_a
     *  Store the first option.
     */
    public function setOptA(string $opt_a): static
    {
        $this->opt_a = $opt_a;

        return $this;
    }

    /**
     * Public function getOptB()
     *  To get the second option.
     *
     * @return string opt_b
     *  Return the second option.
     */
    public function getOptB(): ?string
    {
        return $this->opt_b;
    }

    /**
     * Public function setOptB
     *  To Set the second option.
     *
     * @param string $opt_b
     *  Stores the second option.
     */
    public function setOptB(string $opt_b): static
    {
        $this->opt_b = $opt_b;

        return $this;
    }

    /**
     * Public function getOptC()
     *  To get the third option.
     *
     * @return string opt_c
     *  Return the third option.
     */
    public function getOptC(): ?string
    {
        return $this->opt_c;
    }

    /**
     * Public function setOptC
     *  To Set the third option.
     *
     * @param string $opt_c
     *  Stores the third option.
     */
    public function setOptC(string $opt_c): static
    {
        $this->opt_c = $opt_c;

        return $this;
    }

    /**
     * Public function getOptD
     *  To get the last option.
     *
     * @return string opt_d
     *  Return the last option.
     */
    public function getOptD(): ?string
    {
        return $this->opt_d;
    }

    /**
     * Public function setOptD()
     *  To set the last option
     *
     * @param string $opt_d
     *  To Store the last option
     */
    public function setOptD(string $opt_d): static
    {
        $this->opt_d = $opt_d;

        return $this;
    }

    /**
     * Public function getCorrectOpt()
     *  To get right answer of the question.
     *
     * @return string correct_opt
     *  Return the correct option.
     */
    public function getCorrectOpt(): ?string
    {
        return $this->correct_opt;
    }

    /**
     * Public function setCorredtOpt()
     *  To set correct option.
     *
     * @param string $correct_ans
     *  Stores the correct option.
     */
    public function setCorrectOpt(string $correct_opt): static
    {
        $this->correct_opt = $correct_opt;

        return $this;
    }

    /**
     * Public function getMarksForQuestions()
     *  To get the marks for a single question.
     *
     * @return string marks_for_question
     *  Return marks for the question.
     */
    public function getMarksForQuestion(): ?string
    {
        return $this->marks_for_question;
    }

    /**
     * Public function setMarksForQuestions()
     *  To set the marks for a single question.
     *
     * @param string marks_for_question
     *  Return marks for the question.
     */
    public function setMarksForQuestion(string $marks_for_question): static
    {
        $this->marks_for_question = $marks_for_question;

        return $this;
    }

    public function getExam(): ?Exam
    {
        return $this->exam;
    }

    public function setExam(?Exam $exam): static
    {
        $this->exam = $exam;

        return $this;
    }
}
