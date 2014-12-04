<?php

namespace Bdloc\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LostPasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array(
                'label' => 'Email',
                'attr' => array(
                    'class' => "form-control",
                    'placehoder' => 'Obligatoire'
                    )
                ))
            ->add('submit', 'submit', array(
                'label' => "Valider",
                'attr' => array(
                    'class' => "btn btn-success"
                    )
                )) 
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bdloc\AppBundle\Entity\LostPassword'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bdloc_appbundle_lost';
    }
}