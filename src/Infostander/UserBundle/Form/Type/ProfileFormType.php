<?php
/**
 * @file
 * This file is a part of the Infostander UserBundle which is a modification of the FOSUserBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Infostander\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

/**
 * Class ProfileFormType
 *
 * @package Infostander\UserBundle\Form\Type
 */
class ProfileFormType extends BaseType
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

        $this->buildUserForm($builder, $options);

        $builder->add(
            'current_password',
            'password',
            array(
                'label' => 'form.current_password',
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'class'=>'form-control',
                    'placeholder'=>'form.current_password',
                    'autocomplete' => 'off'
                ),
                'mapped' => false,
                'constraints' => $constraint,
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
        return 'infostander_user_profile';
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                null,
                array(
                    'label' => 'form.username',
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'class'=>'form-control',
                        'placeholder'=>'form.username',
                        'autocomplete' => 'off'
                    )
                )
            )
            ->add(
                'email',
                'email',
                array(
                    'label' => 'form.email',
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'class'=>'form-control',
                        'placeholder'=>'form.email',
                        'autocomplete' => 'off'
                    )
                )
            );
    }
}
