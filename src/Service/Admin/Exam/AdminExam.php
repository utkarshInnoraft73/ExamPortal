<?php

namespace App\Services\Admin\Exam;

/**
 * Class AdminExam{}
 *  To manage the all functionality related to the admin's exams like creathion,
 *  Deletions Update and all.
 *
 */
class AdminExam
{

  /**
   * Public function questionAll();
   *  To fetch all the questions and show.
   *
   * @param array $questions.
   *  All Question from the question Entity.
   *
   * @return array $data.
   *  After fetchin all the data will returned in the form of array.
   */
  public function questionAll(array $questions):array
  {
    $Id = [];
    $quesTitle = [];
    $optA = [];
    $optB = [];
    $optC = [];
    $optD = [];
    $correct = [];
    $marks = [];

    for ($i = 0; $i < count($questions); $i++) {
      array_push($Id, $questions[$i]->getId());
      array_push($quesTitle, $questions[$i]->getQuestion());
      array_push($optA, $questions[$i]->getOptA());
      array_push($optB, $questions[$i]->getOptB());
      array_push($optC, $questions[$i]->getOptC());
      array_push($optD, $questions[$i]->getOptD());
      array_push($correct, $questions[$i]->getCorrectOpt());
      array_push($marks, $questions[$i]->getMarksForQuestion());
    }

    $data = [
      'title' => $quesTitle,
      'optA' => $optA,
      'optB' => $optB,
      'optC' => $optC,
      'optD' => $optD,
      'correct' => $correct,
      'marks' => $marks,
    ];

    return $data;
  }

  /**
   * Public function yourCreatedExam()
   *  To fetch the all exams that are created by the loggedin admin.
   *
   * @param object $user.
   *  The admin who is logged in and want to see their created exa,m lists.
   *
   * @param array $exams.
   *  All exams.
   *
   * @return array
   */
  public function yourCreatedExam(object $user, array $exam): array
  {
    $userName = $user->getEmail();
    $examArr = [];
    $examId = [];
    $examDuration = [];
    $fullMarks = [];
    $passingMarks = [];
    $numOfQues = [];

    for ($i = 0; $i < count($exam); $i++) {
      if ($userName == $exam[$i]->getCreatedBy()) {
        array_push($examId, $exam[$i]->getId());
        array_push($examArr, $exam[$i]->getExamName());
        array_push($fullMarks, $exam[$i]->getTotalMarks());
        array_push($examDuration, $exam[$i]->getDuration());
        array_push($passingMarks, $exam[$i]->getPassingMarks());
        array_push($numOfQues, $exam[$i]->getNoOfQuestios());
      }
    }
    $data = [

      'examId' => $examId,
      'exam' => $examArr,
      'duration' => $examDuration,
      'fullMarks' => $fullMarks,
      'passingMarks' => $passingMarks,
      'numOfQues' => $numOfQues,
    ];

    return $data;
  }

  /**
   * Public funtion yourExamDetail();
   *  To Show the particular exam detail.
   *
   * @param object $exam.
   *  Exam object for which admin is requesting to show more detail.
   *
   * @return array $data.
   *  Returns the all data of the exams.
   */
  public function yourExamDetail(object $exam): array
  {
    $examName = $exam->getExamName();
    $duration = $exam->getDuration();
    $totalQues = $exam->getNoOfQuestios();
    $totalMarks = $exam->getTotalMarks();
    $passingMarks = $exam->getPassingMarks();
    $requiredSchool = $exam->getRequiredSchoolingMarks();
    $requiredGraduation = $exam->getRequiredGraduationMarks();
    $examDate = $exam->getExamDate();
    $data = [
      'examName' => $examName,
      'duration' => $duration,
      'fullMarks' => $totalMarks,
      'passingMarks' => $passingMarks,
      'totalQues' => $totalQues,
      'requiredSchool' => $requiredSchool,
      'requiredGraduation' => $requiredGraduation,
      'examDate' => $examDate,
    ];

    return $data;
  }
}
