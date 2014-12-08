<?php

namespace Bdloc\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreditCardType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('paypalid')
            // ->add('validUntil')
            ->add('cardNumber', null, array(
                'label' => "Numéro de carte de crédit",
                'attr' => array(
                    'placeholder' => 'Obligatoire'
                    )
                ))
            // ->add('codeCVC', null, array(
            //     'label' => "Code CVC",
            //     'attr' => array(
            //         'placeholder' => 'Obligatoire'
            //         )
            //     ))
            ->add('userCard', null, array(
                'label' => "Nom du détenteur de la carte",
                'attr' => array(
                    'placeholder' => 'Obligatoire'
                    )
                ))
            ->add('expirationCard', null, array(
                'label' => "Date d'expiration",
                ))
            // ->add('dateCreated')
            // ->add('dateModified')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bdloc\AppBundle\Entity\CreditCard'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bdloc_appbundle_creditcard';
    }
}
