<?php
/**
 * @file
 * This file is a part of the Infostander AdminBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Infostander\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ScreenType
 *
 * @package Infostander\AdminBundle\Form\Type
 */
class ScreenType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Add the title field.
        $builder->add(
            'title',
            'text',
            array(
                'label' => 'screen.add.title',
                'translation_domain' => 'InfostanderAdminBundle',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'screen.add.title'
                )
            )
        );

        // Add the description field.
        $builder->add(
            'description',
            'textarea',
            array(
                'label' => 'screen.add.description',
                'translation_domain' => 'InfostanderAdminBundle',
                'attr' => array(
                    'rows' => '5',
                    'class' => 'form-control form-last',
                    'placeholder' => 'screen.add.description'
                )
            )
        );

        // Add the save button.
        $builder->add(
            'save',
            'submit',
            array(
                'label' => 'screen.add.save',
                'translation_domain' => 'InfostanderAdminBundle',
                'attr' => array(
                    'class' => 'btn btn-lg btn-primary btn-block'
                )
            )
        );
    }

    /**
     * Set the default options
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Infostander\AdminBundle\Entity\Screen',
        ));
    }

    /**
     * Return the name of the form type
     *
     * @return string
     */
    public function getName()
    {
        return 'screen';
    }
}