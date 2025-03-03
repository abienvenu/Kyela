<?php
namespace App\Form\Type;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['required' => false, 'attr' => ['autofocus' => 'autofocus', 'placeholder' => 'date.name.placeholder']])
            ->add('place', null, ['required' => false, 'attr' => ['placeholder' => 'date.place.placeholder']])
            ->add('date', null, ['required' => false, 'widget' => 'single_text'])
            ->add('time', null, ['required' => false, 'widget' => 'single_text'])
            ->add('subtitle', null, ['required' => false, 'attr' => ['placeholder' => 'date.subtitle.placeholder']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Event::class]);
    }
}
