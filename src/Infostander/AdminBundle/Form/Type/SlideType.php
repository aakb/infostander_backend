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

class SlideType extends AbstractType
{
    /**
     * Builds the form
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
                'label' => 'slide.add.title',
                'translation_domain' => 'InfostanderAdminBundle',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'slide.add.title'
                )
            )
        );

        // Add the description field.
        $builder->add(
            'description',
            'textarea',
            array(
                'label' => 'slide.add.description',
                'translation_domain' => 'InfostanderAdminBundle',
                'attr' => array(
                    'rows' => '5',
                    'class' => 'form-control form-last',
                    'placeholder' => 'slide.add.description'
                )
            )
        );

        // Add the image field.
        $builder->add(
            'image',
            'file',
            array(
                'label' => 'slide.add.image',
                'translation_domain' => 'InfostanderAdminBundle'
            )
        );

        // Add the save button.
        $builder->add(
            'save',
            'submit',
            array(
                'label' => 'slide.add.save',
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
            'data_class' => 'Infostander\AdminBundle\Entity\Slide',
        ));
    }

    /**
     * Return the name of the form type
     *
     * @return string
     */
    public function getName()
    {
        return 'slide';
    }
}