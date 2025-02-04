<?php
namespace App\Form\Type;

use App\Entity\Choice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as BaseChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['attr' => ['autofocus' => 'autofocus', 'placeholder' => "choice.name.placeholder"]])
            ->add('value', null, ['attr' => ['placeholder' => 'choice.name.value']])
            ->add('color', BaseChoiceType::class, ['choices' => [
                'red' => 'red',
                'orange' => 'orange',
                'yellow' => 'yellow',
                'green' => 'green',
                'cyan' => 'cyan',
                'blue' => 'blue',
                'purple' => 'purple',
                'pink' => 'pink',
                'brown' => 'brown',
                'gray' => 'gray',
            ]])
            ->add('icon', IconType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Choice::class]);
    }
}
