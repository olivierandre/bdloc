<?php

namespace Bdloc\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('num')
            /*->add('publisher')
            ->add('isbn')
            ->add('cover')
            ->add('exlibris')
            ->add('pages')
            ->add('board')
            ->add('idbel')
            ->add('stock')
            ->add('dateCreated')
            ->add('dateModified')
            ->add('illustrator')
            ->add('scenarist')
            ->add('colorist')
            ->add('serie')*/
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
