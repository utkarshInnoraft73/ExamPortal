<?php

namespace App\Service\Exam;


use App\Entity\ProfileExamRelated;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Exam
 *  To manage the all functionality and data getting and setting of Exam Entity.
 */
class Exams
{

  /**
   * @var object $em.
   *  To manage the entity state and data.
   */
  private $em;

  /**
   * Contructor __construct.
   *  To set the entity management.
   *
   * @var EntityManagerInterface $em
   *  To set the entity management.
   */
  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  /**
   * Public function getAllExams().
   *  To the all exams that are upcoming.
   *
   * @param array $exams.
   *  All the exams from the exam entity.
   *
   * @param array $profileExam.
   *  All data which user are for which exams.
   *
   * @param int $profileId.
   *  Profile id.
   *
   * @return array $data.
   *  Return array data after fecting all the required data.
   */
  public function getAllExams(array $exams, array $profileExam, object $profile): array
  {
    $profileIdApplied = [];
    $examIdApplied = [];
    for ($i = 0; $i < count($profileExam); $i++) {
      array_push($profileIdApplied, $profileExam[$i]->getProfile()->getId());
      array_push($examIdApplied, $profileExam[$i]->getExam()->getId());
    }
    $profileExamId = array_unique($profileIdApplied);
    $profileInd = $profile->getProfile()->getId();
    $examsId = [];
    $examsName = [];
    $owner = [];
    for ($i = 0; $i < count($exams); $i++) {
      if (in_array($profileInd, $profileExamId)) {
        array_push($examsId, $exams[$i]->getId());
        array_push($examsName, $exams[$i]->getExamName());
        array_push($owner, $exams[$i]->getCreatedBy());
      }
    }
    $data = [
      'examsId' => $examsId,
      'examNames' => $examsName,
      'owners' => $owner,
      'prfileId' => $profileInd
    ];

    return $data;
  }

  /**
   * Public Function applyExams();
   *  To Apply function , first fect the data from Profile Entity and Exam entity
   *
   * Then check if the user is eligible for that exam or not if TRUE then set
   * the data in the profile_exam_related entity else show the error message to
   * the user.
   *
   * @param object $user.
   *  The User details like name, schooling percentage, graduation percentage etc.
   *
   * @param object $exam.
   *  The exam details from exam entity.
   *
   * @return string $applyAlert
   *  After checking and setting return the alert message.
   */
  public function applyExams(object $user, object $exam): string
  {
    $userSchoolMarks = $user->getSchoolingPercent();
    $userGraduationMarks = $user->getGraduationPercent();
    $examRequiredSchoolMarks = $exam->getRequiredSchoolingMarks();
    $examRequiredGraduationMarks = $exam->getRequiredGraduationMarks();
    $applyAlert = "Sorry You are not Eligible for this exam, Required schooling & graduation percentage are $examRequiredSchoolMarks, $examRequiredGraduationMarks and yours are $userSchoolMarks, $userGraduationMarks";

    if ($userSchoolMarks >= $examRequiredSchoolMarks && $userGraduationMarks >= $examRequiredGraduationMarks) {
      $profileExam = new ProfileExamRelated();
      $profileExam->setProfile($user);
      $profileExam->setExam($exam);
      $this->em->persist($profileExam);
      $this->em->flush();
      $applyAlert = "Congratulations. You have applied successfully.";
    }

    return $applyAlert;
  }

  /**
   * Public funtion userExams().
   *  To fetch the data from exam entity for which user is applied already.
   *
   * @param object $user.
   *  User details from the User Entity.
   *
   * @param array $exams.
   *  Get all exam and profile related data from the ProfileExamRelated Entity.
   *
   * @return array $data.
   *  After checking that if logged in user have applied for any upcoming exam
   * not, Return the required data.
   */
  public function userExams(object $profile, array $exams):array
  {
    $profileId = $profile->getProfile()->getId();
    $examList = [];
    $examId = [];
    $owner = [];

    for ($i = 0; $i < count($exams); $i++) {
      if ($profileId == $exams[$i]->getProfile()->getId()) {
        array_push($examId, $exams[$i]->getExam()->getId());
        array_push($examList, $exams[$i]->getExam()->getExamName());
        array_push($owner, $exams[$i]->getExam()->getCreatedBy());
      }
    }
    $data = [
      'profileId' => $profileId,
      'examId' => $examId,
      'examNames' => $examList,
      'owners' => $owner,
    ];

    return $data;
  }

  /**
   * Public Function startExam();
   *  To Start the exam and show the all details about selected exam from there
   *
   * User can start exam.
   *
   * @param object $profile
   *  Profile details of logged in user.
   *
   * @param object $exam
   *  Exam detail for the select exam for which exam user wants to start.
   *
   * @return array $data.
   *  Return exam details for select exams after fetching and checking.
   */
  public function startExam(object $profile, object $exam):array
  {

    $examID = $exam->getId();
    $profileID = $profile->getId();
    $examName = $exam->getExamName();
    $owner = $exam->getCreatedBy();
    $passingMarks = $exam->getPassingMarks();
    $totalMarks = $exam->getTotalMarks();
    $numOfQues = $exam->getNoOfQuestios();
    $duration = $exam->getDuration();

    $data = [
      'examId' => $examID,
      'profileId' => $profileID,
      'passingMarks' => $passingMarks,
      'examName' => $examName,
      'owner' => $owner,
      'totalMarks' => $totalMarks,
      'duration' => $duration,
      'numOfQues' => $numOfQues
    ];

    return $data;
  }

  /**
   * Public function examSubmit()
   *  To submit the exam and validate how many questions user submitted correct,
   *
   * How main answer submitted incorrect by user and how much mark he/she gotten.
   *
   * @param array $ans
   *  User answer.
   *
   * @return array $data.
   *  After check and validate return the data.
   */
  public function examSubmit(array $ans, array $question):array
  {

    $correctAns = 0;
    $incorrectAns = 0;
    $gotenMarks = 0;

    $correct = [];
    $pointedMarks = [];

    for ($i = 0; $i < count($question); $i++) {
      array_push($correct, $question[$i]->getCorrectOpt());
      array_push($pointedMarks, $question[$i]->getMarksForQuestion());
      if ($ans[$i] == $correct[$i]) {
        $gotenMarks += $pointedMarks[$i];
        $correctAns++;
      } else {
        $incorrectAns++;
      }
    }

    $data = [
      'correctans' => $correctAns,
      'incorrectans' => $incorrectAns,
      'gotenmarks' => $gotenMarks,
    ];
    return $data;
  }

  /**
   * Public function allResults()
   * To show the all exam's result of the logged in user.
   *
   * @param array $result
   *  User results.
   *
   * @param object $exam
   *  Exams.
   *
   * @return array $data.
   *  After check and validate return the data.
   */
  public function allResults(array $result, object $exam):array
  {
    $resultArr = [];
    foreach ($result as $value) {
      array_push($resultArr, $value);
    }
    $examIDFromQues = [];
    $resultId = [];
    $correctAns = [];
    $incorrectAns = [];
    $gottenmarks = [];
    for ($i = 0; $i < count($resultArr); $i++) {
      if ($exam->getId() == $resultArr[$i]->getExamId()) {
        array_push($resultId, $result[$i]->getId());
        array_push($examIDFromQues, $result[$i]->getExamId());
        array_push($correctAns, $result[$i]->getCorrectAns());
        array_push($incorrectAns, $result[$i]->getIncorrectAns());
        array_push($gottenmarks, $result[$i]->getGottenMarks());
      }
    }
    $data = [
      'resultId' => $resultId,
      'correctans' => $correctAns,
      'incorrectans' => $incorrectAns,
      'gotenmarks' => $gottenmarks,
    ];

    return $data;
  }
}
