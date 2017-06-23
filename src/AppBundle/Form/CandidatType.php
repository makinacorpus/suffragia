<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Tests\Form\Type\EntityTypeTest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class CandidatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class, array('label'=>'nom'))
            ->add('parti',EntityType::class, array('class' => 'AppBundle:Parti','choice_label' => 'name'))
            ->add('election',EntityType::class, array('class' => 'AppBundle:Election','choice_label' => 'name',"label"=>"election"))
            ->add('blanc')->add('nul')->add('rank') ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Candidat'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_candidat';
    }


}
