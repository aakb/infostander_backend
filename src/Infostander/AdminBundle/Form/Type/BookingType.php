<?php

namespace Infostander\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookingType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('title', 'text', array('label' => 'booking.add.title', 'translation_domain' => 'InfostanderAdminBundle', 'attr' => array('class' => 'form-control', 'placeholder' => 'booking.add.title')));
    $builder->add('startdate', 'text', array('label' => 'booking.add.startdate', 'translation_domain' => 'InfostanderAdminBundle', 'attr' => array('class' => 'form-control', 'placeholder' => 'booking.add.startdate', 'data-format' => 'DD-MM-YYYY HH:mm')));
    $builder->add('enddate', 'text', array('label' => 'booking.add.enddate', 'translation_domain' => 'InfostanderAdminBundle', 'attr' => array('class' => 'form-control', 'placeholder' => 'booking.add.enddate', 'data-format' => 'DD-MM-YYYY HH:mm')));
    $builder->add('save', 'submit', array('label' => 'booking.add.save', 'translation_domain' => 'InfostanderAdminBundle', 'attr' => array('class' => 'btn btn-lg btn-primary btn-block')));

    $choicesArray = array();
    if($options['choice_options']){
      foreach ($options['choice_options'] as $slide) {
        $choicesArray[$slide->getId()] = $slide->getTitle();
      }
    }

    $builder->add('slideid', 'choice', array(
      'choices' => $choicesArray,
      'attr' => array('class'=>'form-control form-choice-control', 'contenteditable'=>'contenteditable')
    ));
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Infostander\AdminBundle\Entity\Booking',
      'choice_options' => array()
    ));
  }

  public function getName()
  {
    return 'booking';
  }
}