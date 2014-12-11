<?php

namespace Bdloc\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class BookType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'label' => 'Titre de la BD (Approximation acceptée ...)'
                ))
            ->add('illustrator', 'text', array(
                'mapped' => false,
                'label' => 'Nom de l\'auteur (Approximation aussi acceptée ...)'
                ))
            // ->add('scenarist')
            // ->add('colorist')
            ->add('serie', null, array(
                'label' => 'Choix de la catégorie (Plusieurs choix possible)',
                'mapped' => false,
                'property' => 'style',
                'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('serie')
                            ->orderBy('serie.style')
                            ->groupBy('serie.style');
                    },
                'expanded' => true,
                'multiple' => true
                ))
            ->add('submit', 'submit', array(
                'label' => "Cherche Lycos !",
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
            'data_class' => 'Bdloc\AppBundle\Entity\Book'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bdloc_appbundle_book';
    }
}
