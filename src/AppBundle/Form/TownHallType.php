<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TownHallType extends AbstractType//ici la classe s'appelle TownHallType et represente une mairie
{
    /**
     * {@inheritdoc}
     * Construction d'une structure d'une mairie via un formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')->add('logo', FileType::class, array('label' => 'Image du logo'))->add('codePostal')->add('circonscription')->add('numeroDepartement');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\TownHall'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_townHall';
    }
}
