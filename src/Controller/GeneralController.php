<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Exam;
use App\Entity\Result;
use App\Entity\Profile;
use App\Entity\Questions;
use App\Form\ProfileType;
use App\Service\Exam\Exams;
use App\Form\ProfileFormType;
use App\Service\Profile\Profiles;
use App\Entity\ProfileExamRelated;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
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
     * Funtion index.
     *  To return the responce of page on routing / .
     *
     * @route path /
     *  Set the route domain/
     *
     * @return Response general/index.html.twig.
     *  Return this page as the responce on route domain name/.
     */
    #[Route('/', name: 'app_general')]
    public function index(): Response
    {
        // Path /template/general/index.html.twig.
        return $this->render('general/index.html.twig');
    }

    /**
     * Function dashboard.
     *  To manage the routing and the functionality of dashboard.
     *
     * @Route /dashboard.
     *  Set the route domain/dashboard.
     *
     * @return Response dashboard/dashboard.html.twig.
     *  Return the page.
     */
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('dashboard/dashboard.html.twig');
    }

    /**
     * Public function profile().
     *  To manage the profile the routing.
     *
     * @Route /profile/{id}.
     *  Set route on the /profile/{id}.
     *
     * @var int $id.
     *  Store the id that will be given in url.
     *
     * @return response.
     *  return respose of the request on the page /profile/profile.html.twig.
     */
    #[Route('/profile/{id}', name: 'app_profile')]
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

        $ProfileData = new Profiles();
        // Return the Response.
        return $this->render('profile/profile.html.twig', $ProfileData->profile($profile));
    }

    /**
     * Public function create-profile().
     *  To manage the create-profile the routing.
     *
     * @Route path (domain/create-profile/{id}).
     *  Set the path (domain/create-profile/{id}).
     *
     * @var Request $request.
     *  The handle the request.
     * @var int $id.
     *  Store the id that will be given in url.
     *
     * @return Response.
     *  return respose of the request on the page /create-profile/create-profile.html.twig.
     */
    #[Route('/create-profile/{id}', name: 'app_createProfile')]
    public function createProfile(Request $request, int $id): Response
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

    /**
     * Public function editProfile().
     *  To manage the edit-profile the routing and functionality.
     *
     * @Route path(/edit-profile/{id})
     *  Set the path(/edit-profile/{id}).
     *
     * @var Request $request.
     *  The request.
     * @var int $id.
     *  Store the id that will be given in url.
     *
     * @return Response.
     *  return respose of the request on the page /profile/create-profile.html.twig.
     */
    #[Route('/edit-profile/{id}', name: 'app_editProfile')]
    public function editprofile(Request $request, int $id): Response
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
     * Public function exams().
     *  Function to manage the exam related routing and functionality.
     *
     * To show the all exams that are avialable till date.
     *
     * @Route path(/exams).
     *  Set the path(/exams).
     *
     * @param SerialixerInterface $serializer.
     *  To seralize the data in the json format.
     *
     * @return Response
     */
    #[Route('/exams', name: 'app_exams')]
    public function exams(SerializerInterface $serializer): Response
    {
        $exams = $this->em->getRepository(Exam::class)->findAll();
        $profileExam = $this->em->getRepository(ProfileExamRelated::class)->findAll();
        $profileId = $this->getUser()->getId();
        $profile = $this->em->getRepository(User::class)->find($profileId);

        // Check if the proile id null then redirect to the create profile.
        if ($profile->getProfile() == NULL) {
            return $this->redirectToRoute('app_createProfile', ['id' => $this->getUser()->getId()]);
        }

        $examData = new Exams($this->em);
        $jsonContent = $serializer->serialize($examData->getAllExams($exams, $profileExam, $profile), 'json');
        $jsonDataArray = json_decode($jsonContent, true);
        return $this->render('exams/exam.html.twig', [
            'jsonData' => $jsonDataArray
        ]);
    }

    /**
     * Public funtion applyExam().
     *  To show all the exam for that user is appling.
     *
     * @Route path (domain/apply-exam/{profileId}/{examId}).
     *  Set the Route path (domain/apply-exam/{profileId}/{examId}).
     *
     * @param int @examId.
     *  Exam Id.
     *
     * @param int profileId.
     *  Profile Id of the user.
     *
     * @return Response
     */
    #[Route('/apply-exam/{profileId}/{examId}', name: 'apply_exam')]
    public function applyExam(int $examId, int $profileId): Response
    {
        $user = $this->em->getRepository(Profile::class)->find($profileId);
        $exam = $this->em->getRepository(Exam::class)->find($examId);
        $examData = new Exams($this->em);
        $alertMsg = $examData->applyExams($user, $exam);

        return $this->render('exams/apply-exam.html.twig', [
            'alertExam' => $alertMsg,
        ]);
    }

    /**
     * Public function appliedExams().
     *  To show user has applied for which exams.
     *
     * @Route Path (domain/user-exams).
     *  Set the Path (domain/user-exams).
     *
     * @param SerializerInterface $serializer.
     *  To serialize the data in json.
     *
     * @param int $id.
     *  User id give in url.
     *
     * @return Response app_appiedExams.
     *  After Checking and fetching the data return the response on the
     *
     * Page (exams/appliedExam.html.twig).
     *
     */
    #[Route('user-exams/{id}', name: 'app_appliedExams')]
    public function appliedExams(SerializerInterface $serializer, int $id): Response
    {
        $profile = $this->em->getRepository(User::class)->find($id);
        $exams = $this->em->getRepository(ProfileExamRelated::class)->findAll();
        // Check if the proile id null then redirect to the create profile.
        if ($profile->getProfile() == NULL)
        {
            return $this->redirectToRoute('app_createProfile', ['id' => $this->getUser()->getId()]);
        }

        $examData = new Exams($this->em);

        $data = $examData->userExams($profile, $exams);
        $jsonContent = $serializer->serialize($data, 'json');
        $jsonDataArray = json_decode($jsonContent, TRUE);
        return $this->render('exams/applied-exam.html.twig', [
            'jsonData' => $jsonDataArray
        ]);
    }

    /**
     * Public function startExam().
     *  To start exam.
     *
     * @Route Path (domain/start-exam/{examId}/{profileID}).
     *  Set the Path (domain/start-exam/{examId}/{profileID}).
     *
     * @param int $id.
     *  User id give in url.
     *
     * @return Response
     *
     */
    #[Route('start-exam/{examId}/{profileID}', name: 'app_startExam')]
    public function startExam(int $examId, int $profileId): Response
    {
        $exam = $this->em->getRepository(Exam::class)->find($examId);
        $profile = $this->em->getRepository(Profile::class)->find($profileId);
        $examData = new Exams($this->em);

        $data = $examData->startExam($profile, $exam);
        return $this->render('exams/start-exam.html.twig', $data);
    }

    /**
     * Public function questions().
     *  To show all the questions of the exam.
     *
     * @Route Path (domain/questions/{userId}/{examId}).
     *  Set the Path (domain/questions/{userId}/{examId}).
     *
     * @param int $examId.
     *  Exam id give in url.
     *
     * @return Response
     */
    #[Route('questions/{userId}/{examId}', name: 'app_questionList')]
    public function questions(int $examId): Response
    {
        $question = $this->em->getRepository(Questions::class)->findAll();

        return $this->render('Question/questions.html.twig', [
            'examId' => $examId,
            'question' => $question,
        ]);
    }

    /**
     * Public function examSubmit().
     *  To appear for the exam
     *
     * @Route Path (domain/exam-submit).
     *  Set the Path (domain/exam-submit).
     *
     * @param Request $request.
     *  To manage the requests.
     *
     * @return Response
     */
    #[Route('/exam-submit/{examId}', name: 'exam_submit')]
    public function examSubmit(Request $request): Response
    {
        $answer = $request->get('answers');
        $ans = [];
        foreach ($answer as $value) {
            array_push($ans, $value);
        }
        $question = $this->em->getRepository(Questions::class)->findAll();
        $examData = new Exams($this->em);
        $data = $examData->examSubmit($ans, $question);
        return $this->redirectToRoute('exam_result', ['gottenMarks' => $data['gotenmarks'], 'correctAns' => $data['correctans'], 'incorrectAns' => $data['incorrectans']]);
    }

    /**
     * Public function result().
     *  To show user result for the exam.
     *
     * @Route Path (domain/result/{gottenMarks}/{correctAns}/{incorrectAns}).
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
     * @return Response
     */
    #[Route('/result/{gottenMarks}/{correctAns}/{incorrectAns}', name: 'exam_result')]
    public function result(SerializerInterface $serializer, string $gottenMarks, string $correctAns, string $incorrectAns): Response
    {

        $data = [
            'correctans' => $correctAns,
            'incorrectans' => $incorrectAns,
            'gotenmarks' => $gottenMarks,
        ];

        $jsonContent = $serializer->serialize($data, 'json');
        $jsonDataArray = json_decode($jsonContent, TRUE);
        return $this->render('result/result.html.twig', [
            'jsonData' => $jsonDataArray
        ]);
    }

    /**
     * Public function results().
     *  To show All result for the exam.
     *
     * @Route Path (domain/open-exam/{userId}/{examId}/{quesId}).
     *  Set the Path (domain/open-exam/{userId}/{examId}/{quesId}).
     *
     * @param SerializerInterface $serializer.
     *  To manage the requests.
     *
     * @param int examId
     *  The edxam id.
     *
     * @return Response
     */
     #[Route('/results/{examId}', name: 'exam_allResult')]
    public function allResult(SerializerInterface $serializer, int $examId): Response
    {
        $result = $this->em->getRepository(Result::class)->findAll();
        $exam = $this->em->getRepository(Exam::class)->find($examId);
        $allResult = new Exams();
        
        $jsonContent = $serializer->serialize($allResult->allResults($result, $exam), 'json');
        $jsonDataArray = json_decode($jsonContent, TRUE);
        return $this->render('result/allResult.html.twig', [
            'jsonData' => $jsonDataArray
        ]);
    }
}
