<?php

namespace AppBundle\Form;

use AppBundle\Entity\MetaElection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetaElectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = MetaElection::getAvailableTypes();
        $type2 = array();
        foreach ($type as $key=>$item){
            $type2[$item] = $item;
        }
        $builder->add('date')->add('type',ChoiceType::class, array(
            'label' => 'Type d\'élection',
            'choices' => array($type2)
        ))->add('nom');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\MetaElection'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_metaelection';
    }


}
