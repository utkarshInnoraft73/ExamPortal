<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Entity\Exam;
use App\Form\ExamType;
use App\Entity\Questions;
use App\Repository\ExamRepository;
use App\Services\Admin\Exam\AdminExam;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AdminController.
 *  To manage and controll the all funtionality related to the admin.
 *
 */
class AdminController extends AbstractController
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
     * Function index().
     *  To Route the admin.
     *
     * @Route Path(/admin).
     *  Set the Path(/admin).
     *
     * @return Response admin/index.html.twig.
     *  The page index.html.twig inside the page admin folder.
     */
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * Public funtion createExam();
     *  To create the exams.
     *
     * @Routh path (admin/create-exam)
     *
     * @param Request $request.
     *  Manage the reques.
     *
     * @return Response (create-exam/create-exam.html.twig).
     *  Return response the to the page create-exam.html.twig.
     */
    #[Route('/admin/create-exam', 'create_exam')]
    public function createExam(Request $request): Response
    {
        $exam = new Exam();
        $form = $this->createForm(ExamType::class, $exam);
        $form->handleRequest($request);
        $exam->setCreatedBy($this->getUser()->getEmail());
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($exam);
                $this->em->flush();
                return $this->redirectToRoute('app_admin');
            }
        }
        catch (Exception $e) {
            throw new Exception("Data not set");
        }


        return $this->render('create-exam/create-exam.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Public funtion allQuestions();
     *  To Show all predefined questons.
     *
     * @Routh path (admin/questions).
     *
     * @param SerializerInterface $serializer.
     *  Manage the reques.
     *
     * @return Response
     */
    #[Route('/admin/questions', 'app_adminQuestion')]
    public function allQuestions(SerializerInterface $serializer): Response
    {
        $questions = $this->em->getRepository(Questions::class)->findAll();
        $questionData = new AdminExam();
        $jsonContent = $serializer->serialize($questionData->questionAll($questions), 'json');
        $jsonDataArray = json_decode($jsonContent, TRUE);
        return $this->render('create-exam/allQuestion.html.twig', [
            'jsonData' => $jsonDataArray
        ]);
    }

    /**
     * Public funtion yourExams();
     *  To create the exams.
     *
     * @Routh path (admin/your-exams/{id})
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
    #[Route('/admin/your-exams/{id}', 'your_exams')]
    public function yourExams(int $id,SerializerInterface $serializer, ExamRepository $er): Response
    {
        $exam = $er->findAll();
        $user = $this->em->getRepository(User::class)->find($id);
        $yourExam = new AdminExam();
        $jsonContent = $serializer->serialize($yourExam->yourCreatedExam($user, $exam), 'json');
        $jsonDataArray = json_decode($jsonContent, TRUE);
        return $this->render('your-exams/your-exams.html.twig', [
            'jsonData' => $jsonDataArray
        ]);
    }

    /**
     * Public funtion deleteExams();
     *  To delete the exams.
     *
     * @Routh path (admin/delete-exams/{examId}).
     *
     * @param int $examId.
     *  Exam Id for that admin is requesting for delete.
     *
     * @return Response (route name your_exams).
     *  Return response.
     */
    #[Route('/admin/delete-exams/{examId}', 'delete_exams')]
    public function deleteExams($examId): Response
    {
        $exam = $this->em->getRepository(Exam::class)->find($examId);
        $this->em->remove($exam);
        $this->em->flush();

        return $this->redirectToRoute('your_exams',['id'=> $this->getUser()->getId()]);
    }

    /**
     * Public funtion yourExamDetail();
     *  To Show the particular exam detail.
     *
     * @Routh path (admin/your-exam-detail/{id}).
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
    #[Route('/admin/your-exam-detail/{examId}', 'adminExam_details')]
    public function yourExamDetail(SerializerInterface $serializer, $examId): Response
    {
        $exam = $this->em->getRepository(Exam::class)->find($examId);
        $yourExamDetails = new AdminExam();
        $jsonContent = $serializer->serialize($yourExamDetails->yourExamDetail($exam), 'json');
        $jsonDataArray = json_decode($jsonContent, TRUE);
        return $this->render('your-exams/exam-details.html.twig', [
            'jsonData' => $jsonDataArray
        ]);
    }
}
