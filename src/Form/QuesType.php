<?php

namespace App\Form;

use App\Entity\Exam;
use App\Entity\Questions;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question')
            ->add('opt_a')
            ->add('opt_b')
            ->add('opt_c')
            ->add('opt_d')
            ->add('correct_opt')
            ->add('marks_for_question')
            ->add('exam', EntityType::class, [
                'class' => Exam::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
