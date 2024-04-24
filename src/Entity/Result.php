<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ResultRepository::class)]
#[Broadcast]
class Result
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    /**
     * @var int $id.
     *  Result Id.
     */
    private ?int $id = NULL;

    #[ORM\Column(nullable: TRUE)]

    /**
     * @var int $user_id.
     *  User Id.
     */
    private ?int $user_id = NULL;

    #[ORM\Column(nullable: TRUE)]

    /**
     * @var int $exam_id.
     *  Exam Id.
     */
    private ?int $exam_id = NULL;

    #[ORM\Column(length: 255, nullable: TRUE)]

    /**
     * @var string $correct_ans.
     *  Number of Correct answer.
     */
    private ?string $correct_ans = NULL;

    #[ORM\Column(length: 255, nullable: TRUE)]

    /**
     * @var string $innoraft_ans.
     *  Number of Inncorrect Answers..
     */
    private ?string $incorrect_ans = NULL;

    #[ORM\Column(length: 255, nullable: TRUE)]

    /**
     * @var string $gotten_marks.
     *  Gotten Marks of user.
     */
    private ?string $gotten_marks = NULL;

    /**
     * Function getid()
     *  To get the result Id.
     *
     * @return int id.
     *  Result Id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Function getUserId()
     *  To get the User Id.
     *
     * @return int userId.
     *  User Id.
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * Function setUserId()
     *  To set the user Id.
     *
     * @param int $user_id.
     *  user Id.
     */
    public function setUserId(?int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Function getExamid()
     *  To get the Exam Id.
     *
     * @return int examId.
     *  Exam Id.
     */
    public function getExamId(): ?int
    {
        return $this->exam_id;
    }

    /**
     * Function setExamId()
     *  To set the  examId.
     *
     * @param int examId.
     *  Exam Id.
     */
    public function setExamId(?int $exam_id): static
    {
        $this->exam_id = $exam_id;

        return $this;
    }

    /**
     * Function getCorrectAns()
     *  To get the  number of correct_ans.
     *
     * @return string correct_ans.
     *  Number of correct ans.
     */
    public function getCorrectAns(): ?string
    {
        return $this->correct_ans;
    }

    /**
     * Function setCorrectAns()
     *  To set the  number of correct_ans.
     *
     * @param string correct_ans.
     *  Number of correct ans.
     */
    public function setCorrectAns(?string $correct_ans): static
    {
        $this->correct_ans = $correct_ans;

        return $this;
    }

    /**
     * Function getIncorrectAns()
     *  To get the  number of incorrect_ans.
     *
     * @return string incorrect_ans.
     *  Number of incorrect ans.
     */
    public function getIncorrectAns(): ?string
    {
        return $this->incorrect_ans;
    }

    /**
     * Function setIncorrectAns()
     *  To set the  number of incorrect_ans.
     *
     * @param string incorrect_ans.
     *  Number of incorrect ans.
     */
    public function setIncorrectAns(?string $incorrect_ans): static
    {
        $this->incorrect_ans = $incorrect_ans;

        return $this;
    }

    /**
     * Function getGottenMarks()
     *  To get the  number of gotten marks by the user.
     *
     * @return string gotten_marks.
     *  Gotten marks by the user.
     */
    public function getGottenMarks(): ?string
    {
        return $this->gotten_marks;
    }

    /**
     * Function setGottenMarks()
     *  To set the  number of gotten marks by the user.
     *
     * @param string gotten_marks.
     *  Gotten marks by the user.
     */
    public function setGottenMarks(?string $gotten_marks): static
    {
        $this->gotten_marks = $gotten_marks;

        return $this;
    }
}
