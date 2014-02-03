<?php

namespace Infostander\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ScreenType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('title');
    $builder->add('description');
    $builder->add('save', 'submit');
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Infostander\AdminBundle\Entity\Screen',
    ));
  }

  public function getName()
  {
    return 'screen';
  }
}