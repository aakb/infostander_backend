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
    $builder->add('slideid', 'text', array('label' => 'booking.add.slideid', 'translation_domain' => 'InfostanderAdminBundle', 'attr' => array('class' => 'form-control', 'placeholder' => 'booking.add.slideid')));
    $builder->add('startdate', 'text', array('label' => 'booking.add.startdate', 'translation_domain' => 'InfostanderAdminBundle', 'attr' => array('class' => 'form-control', 'placeholder' => 'booking.add.startdate', 'data-format' => 'DD-MM-YYYY HH:mm')));
    $builder->add('enddate', 'text', array('label' => 'booking.add.enddate', 'translation_domain' => 'InfostanderAdminBundle', 'attr' => array('class' => 'form-control', 'placeholder' => 'booking.add.enddate', 'data-format' => 'DD-MM-YYYY HH:mm')));
    $builder->add('save', 'submit', array('label' => 'booking.add.save', 'translation_domain' => 'InfostanderAdminBundle', 'attr' => array('class' => 'btn btn-lg btn-primary btn-block')));
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Infostander\AdminBundle\Entity\Booking',
    ));
  }

  public function getName()
  {
    return 'booking';
  }
}