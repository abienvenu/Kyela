<?php

namespace Abienvenu\KyelaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\ButtonBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class FormActionsType
 *
 * Adds support for form actions, printing buttons in a single line, and correctly offset.
 *
 */
class FormActionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['buttons'] as $name => $config) {
            $this->addButton($builder, $name, $config);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->count() == 0) {
            return;
        }

        array_map(array($this, 'validateButton'), $form->all());
    }

    protected function addButton($builder, $name, $config)
    {
    	$options = (isset($config['options']))? $config['options'] : [];
        $builder->add($name, $config['type'], $options);
    }

    protected function validateButton(FormInterface $field)
    {
        if (!$field instanceof Button) {
            throw new \InvalidArgumentException("Children of FormActionsType must be instances of the Button class");
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'buttons'        => array(),
                'options'        => array(),
                'mapped'         => false,
            ));
    }

    public function getName()
    {
        return 'form_actions';
    }
}
