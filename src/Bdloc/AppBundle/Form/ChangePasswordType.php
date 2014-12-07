<?php

namespace Bdloc\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangePasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', 'password', array(
                'required' => true,
                'label' => 'Votre ancien mot de passe'
                ))
            ->add('newPassword', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Les mots de passe doivent correspondre',
                'options' => array(
                    'required' => true),
                    'first_options'  => array('label' => 'Nouveau mot de passe'),
                    'second_options' => array('label' => 'Merci de le confirmer')
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
            'data_class' => 'Bdloc\AppBundle\Entity\ChangePassword'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bdloc_appbundle_change_password';
    }
}