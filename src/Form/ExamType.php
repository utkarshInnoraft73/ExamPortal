<?php

namespace App\Form;

use App\Entity\Exam;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ExamType.
 *  To bulid and manage the exam form.
 */
class ExamType extends AbstractType
{
    /**
     * Public function buildForm()
     *  To build the exam form.
     *
     * @param FormBuilderInterface $builder.
     *  To build the form.
     *
     * @param array $options.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('exam_name')
            ->add('passing_marks')
            ->add('exam_date')
            ->add('required_schooling_marks')
            ->add('required_graduation_marks')
            ->add('total_marks')
            ->add('no_of_questios')
            ->add('duration');
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exam::class,
        ]);
    }
}
