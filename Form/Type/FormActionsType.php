<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormActionsType
 *
 * Adds support for form actions, printing buttons in a single line, and correctly offset.
 *
 */
class FormActionsType extends AbstractType
{
    /**
     * Add buttons to the form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($options['buttons'] as $name => $config) {
            $this->addButton($builder, $name, $config);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if ($form->count() == 0) {
            return;
        }

        array_map([$this, 'validateButton'], $form->all());
    }

    protected function addButton(FormBuilderInterface $builder, $name, $config): void
    {
        $options = (isset($config['options']))? $config['options'] : [];
        $builder->add($name, $config['type'], $options);
    }

    protected function validateButton(FormInterface $field): void
    {
        if (!$field instanceof Button) {
            throw new \InvalidArgumentException("Children of FormActionsType must be instances of the Button class");
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                'buttons'        => [],
                'options'        => [],
                'mapped'         => false,
            ]);
    }
}
