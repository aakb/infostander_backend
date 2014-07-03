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
 * Class BookingType
 *
 * @package Infostander\AdminBundle\Form\Type
 */
class BookingType extends AbstractType
{
    /**
     * Builds the form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Add title field.
        $builder->add(
            'title',
            'text',
            array(
                'label' => 'booking.add.title',
                'translation_domain' => 'InfostanderAdminBundle',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'booking.add.title'
                )
            )
        );

        // Add start date field.
        $builder->add(
            'startdate',
            'text',
            array(
                'label' => 'booking.add.startdate',
                'translation_domain' => 'InfostanderAdminBundle',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'booking.add.startdate',
                    'data-format' => 'DD-MM-YYYY HH:mm',
                    'readonly' => 'readonly'
                )
            )
        );

        // Add end date field.
        $builder->add(
            'enddate',
            'text',
            array(
                'label' => 'booking.add.enddate',
                'translation_domain' => 'InfostanderAdminBundle',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'booking.add.enddate',
                    'data-format' => 'DD-MM-YYYY HH:mm',
                    'readonly' => 'readonly'
                )
            )
        );

        // Add save button.
        $builder->add(
            'save',
            'submit',
            array(
                'label' => 'booking.add.save',
                'translation_domain' => 'InfostanderAdminBundle',
                'attr' => array(
                    'class' => 'btn btn-lg btn-primary btn-block'
                )
            )
        );

        // Make array of choices for the template.
        $choicesArray = array();
        if ($options['choice_options']) {
            foreach ($options['choice_options'] as $slide) {
                $choicesArray[$slide->getId()] = $slide->getTitle();
            }
        }

        // Add slide choice.
        $builder->add(
            'slideid',
            'choice',
            array(
                'choices' => $choicesArray,
                'attr' => array(
                    'class' => 'form-control form-choice-control',
                    'contenteditable' => 'contenteditable'
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
            'data_class' => 'Infostander\AdminBundle\Entity\Booking',
            'choice_options' => array()
        ));
    }

    /**
     * Return the name of the form type
     *
     * @return string
     */
    public function getName()
    {
        return 'booking';
    }
}