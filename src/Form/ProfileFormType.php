<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProfileFormType.
 *  To build the profile form.
 */
class ProfileFormType extends AbstractType
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
            ->add('name')
            ->add('schooling_percent')
            ->add('graduation_percent')
            ->add('resume_link')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
