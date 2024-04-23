<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Exam;
use App\Entity\Profile;
use App\Entity\Questions;
use App\Form\ProfileType;
use App\Form\ProfileFormType;
use App\Entity\ProfileExamRelated;
use App\Entity\Result;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Solution;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class GeneralController.
 *  To controll the general rounting and functionality.
 */
class GeneralController extends AbstractController
{
    /**
     * @var object $em
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
     * Set route name of app_general on url /.
     */
    #[Route('/', name: 'app_general')]

    /**
     * Funtion index.
     *  To return the responce of page on routing / .
     *
     * @return response general/index.html.twig.
     *  Return this page as the responce on route domain name/.
     */
    public function index(): Response
    {
        // Path /template/general/index.html.twig.
        return $this->render('general/index.html.twig');
    }

    /**
     * Route /dashboard.
     *  Return app_dashboad on the /dashboard route.
     */
    #[Route('/dashboard', name: 'app_dashboard')]

    /**
     * Function dashboard.
     *  To manage the routing and the functionality of dashboard.
     *
     * @return response dashboard/dashboard.html.twig.
     *  Return the page.
     */
    public function dashboard(): Response
    {

        return $this->render('dashboard/dashboard.html.twig');
    }

    /**
     * Set route on the /profile/{id}.
     *  return the page app_profile on route /profile/{id}.
     */
    #[Route('/profile/{id}', name: 'app_profile')]

    /**
     * Public function profile().
     *  To manage the profile the routing.
     *
     * @var Request $request.
     *  The request.
     * @var int $id.
     *  Store the id that will be given in url.
     *
     * @return response.
     *  return respose of the request on the page /profile/profile.html.twig.
     */
    public function profile(int $id): Response
    {
        // Fetch the user from the User Entity.
        $user = $this->em->getRepository(User::class)->find($id);

        // Find the Profile of user with id = $id.
        $profile = $user->getProfile();

        // Check if the proile id null then redirect to the create profile.
        if ($profile == NULL) {
            return $this->redirectToRoute('app_createProfile', ['id' => $id]);
        }

        // Fetcing the data from entity.
        $profileId = $profile->getId();
        $profile = $user->getProfile();
        $userName = $profile->getName();
        $userSchool = $profile->getSchoolingPercent();
        $userGraduation = $profile->getGraduationPercent();
        $userResume = $profile->getResumeLink();

        // Return the Response.
        return $this->render('profile/profile.html.twig', [
            'profileId' => $profileId,
            'userName' => $userName,
            'userSchool' => $userSchool,
            'userGraduation' => $userGraduation,
            'userResume' => $userResume,
        ]);
    }

    /**
     * Route for create profile.
     * path (domain/create-profile/{id}).
     * path name = app_createProfile.
     *
     */
    #[Route('/create-profile/{id}', name: 'app_createProfile')]

    /**
     * Public function create-profile().
     *  To manage the create-profile the routing.
     *
     * @var Request $request.
     *  The handle the request.
     * @var int $id.
     *  Store the id that will be given in url.
     *
     * @return response.
     *  return respose of the request on the page /create-profile/create-profile.html.twig.
     */
    public function createProfile(Request $request, int $id): response
    {
        // Fetching the user.
        $user = $this->em->getRepository(User::class)->find($id);

        // Create the instance of Profile class.
        $profile = new Profile();

        // Create the from for the ProfileFormType class.
        $form = $this->createForm(ProfileFormType::class, $profile);
        $form->handleRequest($request);

        // Setting the proifle.
        $profile->setUser($user);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($profile);
            $this->em->flush();

            // Render to the dashboard.
            return $this->redirectToRoute('app_dashboard');
        }
        return $this->render('profile/create-profile.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/edit-profile/{id}', name: 'app_editProfile')]

    /**
     * Public function editProfile().
     *  To manage the edit-profile the routing and functionality.
     *
     * @var Request $request.
     *  The request.
     * @var int $id.
     *  Store the id that will be given in url.
     *
     * @return response.
     *  return respose of the request on the page /profile/create-profile.html.twig.
     */
    public function editprofile(Request $request, int $id): response
    {
        $profile = $this->em->getRepository(Profile::class)->find($id);
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($profile);
            $this->em->flush();
        }
        return $this->render('profile/create-profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Route for all available exams.
     * Route path(/exams).
     * Route name(/app_exams).
     */
    #[Route('/exams', name: 'app_exams')]

    /**
     * Public function exams.
     *  Function to manage the exam related routing and functionality.
     *
     * To show the all exams that are avialable till date.
     *
     * @param SerialixerInterface $serializer.
     *  To seralize the data in the json format.
     *
     * @return Response
     */
    public function exams(SerializerInterface $serializer): Response
    {
        $exams = $this->em->getRepository(Exam::class)->findAll();
        $profileExam = $this->em->getRepository(ProfileExamRelated::class)->findAll();
        $profileExamId = [];
        for ($i = 0; $i < count($profileExam); $i++) {
            array_push($profileExamId, $profileExam[$i]->getProfile()->getId());
        }
        $profileId = $this->getUser()->getId();
        $profile = $this->em->getRepository(User::class)->find($profileId);

        // Check if the proile id null then redirect to the create profile.
        if ($profile->getProfile() == NULL) {
            return $this->redirectToRoute('app_createProfile', ['id' => $this->getUser()->getId()]);
        }

        $profileInd = $profile->getProfile()->getId();
        $examsId = [];
        $examsName = [];
        $owner = [];
        for ($i = 0; $i < count($exams); $i++) {
            if ($profileInd != $profileExamId) {
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
        $jsonContent = $serializer->serialize($data, 'json');
        $jsonDataArray = json_decode($jsonContent, true);
        return $this->render('exams/exam.html.twig', [
            'jsonData' => $jsonDataArray
        ]);
    }

    /**
     * Routing for apply exam.
     * Route path (domain/apply-exam/{profileId}/{examId}).
     * Route name(apply_exam).
     */
    #[Route('/apply-exam/{profileId}/{examId}', name: 'apply_exam')]

    /**
     * Public funtion applyExam.
     *  To show all the exam for that user is appling.
     *
     * @param Request $request.
     *  To manage the requests.
     *
     * @param int @examId.
     *  Exam Id.
     *
     * @param int profileId.
     *  Profile Id of the user.
     *
     * @return Response
     */
    public function applyExam(int $examId, int $profileId): Response
    {

        $user = $this->em->getRepository(Profile::class)->find($profileId);

        $userSchoolMarks = $user->getSchoolingPercent();
        $userGraduationMarks = $user->getGraduationPercent();
        $exam = $this->em->getRepository(Exam::class)->find($examId);
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
        return $this->render('exams/apply-exam.html.twig', [
            'alertExam' => $applyAlert,
        ]);
    }

    /**
     * Route for the user-exams exams list for user.
     * Path (domain/user-exams).
     * Route name (app_appliedExams).
     *
     */
    #[Route('user-exams/{id}', name: 'app_appliedExams')]

    /**
     * Public function appliedExams().
     *  To show user has applied for which exams.
     *
     * @param SerializerInterface $serializer.
     *  To serialize the data in json.
     *
     * @param int $id.
     *  User id give in url.
     *
     * @return response app_appiedExams.
     *  After Checking and fetching the data return the response on the
     *
     * page (exams/appliedExam.html.twig).
     *
     */
    public function appliedExams(SerializerInterface $serializer, $id): response
    {
        $profile = $this->em->getRepository(User::class)->find($id);
        // Check if the proile id null then redirect to the create profile.
        if ($profile->getProfile() == NULL) {
            return $this->redirectToRoute('app_createProfile', ['id' => $this->getUser()->getId()]);
        }
        $profileId = $profile->getProfile()->getId();

        $exams = $this->em->getRepository(ProfileExamRelated::class)->findAll();
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
        $jsonContent = $serializer->serialize($data, 'json');
        $jsonDataArray = json_decode($jsonContent, TRUE);
        return $this->render('exams/applied-exam.html.twig', [
            'jsonData' => $jsonDataArray
        ]);
    }

    /**
     * Route for the start exams.
     * Path (domain/start-exam/{examId}/{profileID}).
     * Route name (app_startExam).
     *
     */
    #[Route('start-exam/{examId}/{profileID}', name: 'app_startExam')]

    /**
     * Public function startExam().
     *  To start exam.
     *
     * @param int $id.
     *  User id give in url.
     *
     * @return response
     *
     */
    public function startExam($examId, $profileId): response
    {
        $exam = $this->em->getRepository(Exam::class)->find($examId);
        $profile = $this->em->getRepository(Profile::class)->find($profileId);

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
        return $this->render('exams/start-exam.html.twig', $data);
    }

    /**
     * Route for questions.
     * Path (domain/questions/{userId}/{examId}).
     * Route name (app_questionList).
     *
     */
    #[Route('questions/{userId}/{examId}', name: 'app_questionList')]

    /**
     * Public function questions().
     *  To show all the questions of the exam.
     *
     * @param int $id.
     *  User id give in url.
     *
     * @return response exam_result.
     *  After submission page will redirect to the exam_result.
     *
     *
     */
    public function questions(int $examId): response
    {
        $question = $this->em->getRepository(Questions::class)->findAll();

        return $this->render('Question/questions.html.twig', [
            'examId' => $examId,
            'question' => $question,
        ]);
    }

    /**
     * Route for result.
     * Path (domain/exam-submit).
     * Route name (exam_submit).
     *
     */
    #[Route('/exam-submit/{examId}', name: 'exam_submit')]

    /**
     * Public function examSubmit().
     *  To appear for the exam
     *
     * @param Request $request.
     *  To manage the requests.
     *
     * @return response
     */
    public function examSubmit(Request $request, int $examId): response
    {
        $answer = $request->get('answers');
        $ans = [];
        foreach ($answer as $value) {
            # code...
            array_push($ans, $value);
        }

        $correctAns = 0;
        $incorrectAns = 0;
        $gotenMarks = 0;
        $question = $this->em->getRepository(Questions::class)->findAll();

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
        $result = new Result();
        $result->setExamId($examId);
        $result->setUserId($this->getUser()->getId());
        $result->setCorrectAns($correctAns);
        $result->setIncorrectAns($incorrectAns);
        $result->setGottenMarks($correctAns);
        $this->em->persist($result);
        $this->em->flush();

        return $this->redirectToRoute('exam_result', ['resultId'=>$result->getId()]);
    }

    /**
     * Route for the applied exams list for user.
     * Path (domain/open-exam/{userId}/{examId}/{quesId}).
     * Route name (app_startExam).
     *
     */
    #[Route('/result/{resultId}', name: 'exam_result')]

    /**
     * Public function result().
     *  To show user result for the exam.
     *
     * @param SerializerInterface $serializer.
     *  To manage the requests.
     *
     * @param string $gottenmarks.
     *  User Marks gotten.
     *
     * @param string inccorectans.
     *  Numbers of question that are answered wrong.
     *
     * @param string correctans.
     *  number questions that are answered correct.
     *
     * @return response
     */
    public function result(SerializerInterface $serializer, int $resultId): response
    {
        // $result = $this->em->getRepository(Solution::class)->find($resultId);
        $result = $this->em->getRepository(Result::class)->find($resultId);

        $data = [
            'correctans' => $result->getCorrectAns(),
            'incorrectans' => $result->getIncorrectAns(),
            'gotenmarks' => $result->getGottenMarks(),
        ];
        $jsonContent = $serializer->serialize($data, 'json');
        $jsonDataArray = json_decode($jsonContent, TRUE);
        return $this->render('result/result.html.twig', [
            'jsonData' => $jsonDataArray
        ]);
    }

    /**
     * Route for the applied exams list for user.
     * Path (domain/open-exam/{userId}/{examId}/{quesId}).
     * Route name (app_startExam).
     *
     */
    #[Route('/results/{examId}', name: 'exam_allResult')]

    /**
     * Public function result().
     *  To show user result for the exam.
     *
     * @param SerializerInterface $serializer.
     *  To manage the requests.
     *
     * @param string $gottenmarks.
     *  User Marks gotten.
     *
     * @param string inccorectans.
     *  Numbers of question that are answered wrong.
     *
     * @param string correctans.
     *  number questions that are answered correct.
     *
     * @return response
     */
    public function allResult(SerializerInterface $serializer, int $examId): response
    {
        $result = $this->em->getRepository(Result::class)->findAll();
        $exam = $this->em->getRepository(Exam::class)->find($examId);
        $resultArr = [];
        foreach ($result as $value) {
         array_push($resultArr, $value);
        }
        $examIDFromQues = [];
        $resultId = [];
        $correctAns = [];
        $incorrectAns = [];
        $gottenmarks = [];
        for($i = 0; $i <count($resultArr); $i++){
            if($exam->getId() == $resultArr[$i]->getExamId()){
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
        $jsonContent = $serializer->serialize($data, 'json');
        $jsonDataArray = json_decode($jsonContent, TRUE);
        return $this->render('result/allResult.html.twig', [
            'jsonData' => $jsonDataArray
        ]);
    }
}
