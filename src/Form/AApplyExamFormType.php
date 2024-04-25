<?php

namespace App\Form;

use App\Entity\Exam;
use App\Entity\Profile;
use App\Entity\ProfileExamRelated;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AApplyExamFormType.
 *  To Apply examFromType.
 */

class AApplyExamFormType extends AbstractType
{
    /**
     * Public Function builForm().
     *  To build the form for apply the exam by the user.
     *
     * @param FormBuilderInterface $builder
     *  To build the form. the is predifined form from symfony.
     *
     * @param array $options.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('profile', EntityType::class, [
                'class' => Profile::class,
                'choice_label' => 'id',
            ])
            ->add('exam', EntityType::class, [
                'class' => Exam::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProfileExamRelated::class,
        ]);
    }
}
