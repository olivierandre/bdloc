<?php

namespace Bdloc\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('username', null, array(
                'label' => 'Pseudo',
                'attr' => array(
                    'placeholder' => 'Obligatoire'
                    )
                ))
            ->add('email', 'email', array(
                'label' => 'Votre email',
                'attr' => array(
                    'placeholder' => 'Obligatoire'
                    )
                ))
            ->add('firstName', null, array(
                'label' => 'Prénom'
                ))
            ->add('lastName', null, array(
                'label' => 'Nom'
                ))
            ->add('zip', null, array(
                'label' => 'Code postal'
                ))
            ->add('address', null, array(
                'label' => 'Votre adresse'
                ))
            ->add('phone', null, array(
                'label' => 'Numéro de téléphone'
                ))
            // ->add('password', 'repeated', array(
            //     'type' => 'password',
            //     'invalid_message' => 'Les mots de passe doivent correspondre',
            //     'options' => array(
            //         'required' => true),
            //         'first_options'  => array('label' => 'Mot de passe'),
            //         'second_options' => array('label' => 'Mot de passe (validation)')
            //     ))
            ->add('submit', 'submit', array(
                'label' => "Mettre à jour vos données",
                'attr' => array(
                    'class' => "btn-success"
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
            'data_class' => 'Bdloc\AppBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bdloc_appbundle_user';
    }
}
