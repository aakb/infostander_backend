<?php
/**
 * @file
 * This file is a part of the Infostander UserBundle which is a modification of the FOSUserBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Infostander\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

/**
 * Class RegistrationFormType
 *
 * @package Infostander\UserBundle\Form\Type
 */
class RegistrationFormType extends BaseType
{
    /**
     * Builds the form
     *
     * Differences from FOSUserBundle are added attributes to the input fields.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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

    /**
     * Returns the name of the form
     *
     * @return string
     */
    public function getName()
    {
        return 'infostander_user_registration';
    }
}
