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
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use FOS\UserBundle\Form\Type\ChangePasswordFormType as BaseType;

/**
 * Class ChangePasswordFormType
 *
 * Overrides the ChangePasswordFormType from the FOSUserBundle
 *
 * @package Infostander\UserBundle\Form\Type
 */
class ChangePasswordFormType extends BaseType
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
        if (class_exists('Symfony\Component\Security\Core\Validator\Constraints\UserPassword')) {
            $constraint = new UserPassword();
        } else {
            // Symfony 2.1 support with the old constraint class
            $constraint = new OldUserPassword();
        }

        $builder->add('current_password', 'password', array(
            'label' => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'attr' => array(
                'class'=>'form-control',
                'placeholder' => 'form.current_password'
            ),
            'mapped' => false,
            'constraints' => $constraint,
        ));
        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'form.new_password', 'attr' => array(
                'class'=>'form-control',
                'placeholder' => 'form.new_password'
            ),),
            'second_options' => array('label' => 'form.new_password_confirmation', 'attr' => array(
                'class'=>'form-control',
                'placeholder' => 'form.new_password_confirmation'
            ),),
            'invalid_message' => 'fos_user.password.mismatch',
        ));
    }

    /**
     * Returns the name of the form
     *
     * @return string
     */
    public function getName()
    {
        return 'infostander_user_change_password';
    }
}
