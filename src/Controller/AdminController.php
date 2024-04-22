<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Exam;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\ExamRepository;
use App\Entity\User;
use App\Form\ExamType;
use App\Entity\Questions;

/**
 * Class AdminController.
 *  To manage and controll the all funtionality related to the admin.
 *
 */
class AdminController extends AbstractController
{

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
     * Route for /admin.
     * Path(/admin).
     * Route name (app_name).
     */
    #[Route('/admin', name: 'app_admin')]

    /**
     * Function index().
     *  To Route the admin.
     *
     * @return Response admin/index.html.twig.
     *  The page index.html.twig inside the page admin folder.
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * Route for create exam.
     * Routh path (admin/create-exam).
     * Routh name (create_exam).
     *
     */
    #[Route('/admin/create-exam', 'create_exam')]

    /**
     * Public funtion createExam();
     *  To create the exams.
     *
     * @param Request $request.
     *  Manage the reques.
     *
     * @return Response (create-exam/create-exam.html.twig).
     *  Return response the to the page create-exam.html.twig.
     */
    public function createExam(Request $request): Response
    {
        $exam = new Exam();
        $form = $this->createForm(ExamType::class, $exam);
        $form->handleRequest($request);
        $exam->setCreatedBy($this->getUser()->getEmail());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($exam);
            $this->em->flush();
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('create-exam/create-exam.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Route for questions
     * Routh path (admin/questions).
     * Routh name (app_adminQuestion).
     *
     */
    #[Route('/admin/questions', 'app_adminQuestion')]

    /**
     * Public funtion allQuestions();
     *  To Show all predefined questons.
     *
     * @param SerializerInterface $serializer.
     *  Manage the reques.
     *
     * @return Response
     */
    public function allQuestions(SerializerInterface $serializer): Response
    {
        $questions = $this->em->getRepository(Questions::class)->findAll();
        $Id = [];
        $quesTitle = [];
        $A = [];
        $B = [];
        $C = [];
        $D = [];
        $correct = [];
        $marks = [];

        for ($i = 0; $i < count($questions); $i++) {
            array_push($Id, $questions[$i]->getId());
            array_push($quesTitle, $questions[$i]->getQuestion());
            array_push($A, $questions[$i]->getOptA());
            array_push($B, $questions[$i]->getOptB());
            array_push($C, $questions[$i]->getOptC());
            array_push($D, $questions[$i]->getOptD());
            array_push($correct, $questions[$i]->getCorrectOpt());
            array_push($marks, $questions[$i]->getMarksForQuestion());
        }

        $data = [
            'title' => $quesTitle,
            'A' => $A,
            'B' => $B,
            'C' => $C,
            'D' => $D,
            'correct' => $correct,
            'marks' => $marks,
        ];
        $jsonContent = $serializer->serialize($data, 'json');
        $jsonDataArray = json_decode($jsonContent, true);
        return $this->render('create-exam/allQuestion.html.twig', [
            'jsonData' => $jsonDataArray
        ]);
    }

    /**
     * Route for exams list created by admin.
     * Routh path (admin/your-exams/{id}).
     * Routh name (your_exams).
     *
     */
    #[Route('/admin/your-exams/{id}', 'your_exams')]

    /**
     * Public funtion yourExams();
     *  To create the exams.
     *
     * @param int id.
     *  User id.
     *
     * @param SerializerInterface $serializer.
     *  To serialize the data array to the jsonData.
     *
     * @param ExamRepository $er.
     *  To exam repository exam entity.
     *
     * @return Response
     */
    public function yourExams(int $id,SerializerInterface $serializer, ExamRepository $er): Response
    {
        $exam = $er->findAll();
        $user = $this->em->getRepository(User::class)->find($id);
        $userName = $user->getProfile()->getName();
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
        $jsonContent = $serializer->serialize($data, 'json');
        $jsonDataArray = json_decode($jsonContent, true);
        return $this->render('your-exams/your-exams.html.twig', [
            'jsonData' => $jsonDataArray
        ]);
    }

    /**
     * Route for my delete exams.
     * Routh path (admin/delete-exams/{examId}).
     * Routh name (delete_exams).
     *
     */
    #[Route('/admin/delete-exams/{examId}', 'delete_exams')]

    /**
     * Public funtion deleteExams();
     *  To delete the exams.
     *
     * @param int $examId.
     *  Exam Id for that admin is requesting for delete.
     *
     * @return Response (route name your_exams).
     *  Return response.
     */
    public function deleteExams($examId): Response
    {
        $exam = $this->em->getRepository(Exam::class)->find($examId);
        $this->em->remove($exam);
        $this->em->flush();

        return $this->redirectToRoute('your_exams',['id'=> $this->getUser()->getId()]);
    }

    /**
     * Route for my exam detail for particular exam.
     * Routh path (admin/your-exam-detail/{id}).
     * Routh name (adminExam_details).
     *
     */
    #[Route('/admin/your-exam-detail/{examId}', 'adminExam_details')]

    /**
     * Public funtion yourExamDetail();
     *  To Show the particular exam detail.
     *
     * @param Request $request.
     *  Manage the request.
     *
     * @param int $examId.
     *  Exam id for that exam we are looking for.
     *
     * @return Response
     *  Return response the to the page your-exam.html.twig.
     */
    public function yourExamDetail(SerializerInterface $serializer, $examId): Response
    {
        $exam = $this->em->getRepository(Exam::class)->find($examId);
        $examName = $exam->getExamName();
        $duration = $exam->getDuration();
        $totalQues = $exam->getNoOfQuestios();
        $totalMarks = $exam->getTotalMarks();
        $passingMarks = $exam->getPassingMarks();
        $data = [
            'examName' => $examName,
            'duration' => $duration,
            'fullMarks' => $totalMarks,
            'passingMarks' => $passingMarks,
            'totalQues' => $totalQues,
        ];
        $jsonContent = $serializer->serialize($data, 'json');
        $jsonDataArray = json_decode($jsonContent, true);
        return $this->render('your-exams/exam-details.html.twig', [
            'jsonData' => $jsonDataArray
        ]);
    }
}
