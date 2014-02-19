<?php

namespace Infostander\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                'email',
                array(
                    'label' => 'form.email',
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'class'=>'form-control',
                        'placeholder' => 'form.email',
                        'autocomplete' => 'off'
                    )
                )
            )
            ->add(
                'username',
                null,
                array(
                    'label' => 'form.username',
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'class'=>'form-control',
                        'placeholder' => 'form.username',
                        'autocomplete' => 'off'
                    )
                )
            )
            ->add(
                'plainPassword',
                'repeated',
                array(
                    'type' => 'password',
                    'options' => array(
                        'translation_domain' => 'FOSUserBundle'
                    ),
                    'first_options' => array(
                        'label' => 'form.password',
                        'attr' => array(
                            'class'=>'form-control',
                            'placeholder'=>'form.password',
                            'autocomplete' => 'off')
                    ),
                    'second_options' => array(
                        'label' => 'form.password_confirmation',
                        'attr' => array(
                            'class'=>'form-control form-register form-last',
                            'placeholder'=>'form.password_confirmation',
                            'autocomplete' => 'off'
                        )
                    ),
                'invalid_message' => 'fos_user.password.mismatch',
                )
            );
    }

    public function getName()
    {
        return 'infostander_user_registration';
    }
}
