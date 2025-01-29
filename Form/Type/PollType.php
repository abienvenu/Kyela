<?php
namespace App\Form\Type;

use App\Entity\Poll;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PollType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url')
            ->add('title', null, ['attr' => ['autofocus' => 'autofocus']])
            ->add('headLines', TextareaType::class, ['required' => false, 'attr' => ['rows' => 7, 'placeholder' => 'poll.headlines.placeholder']])
            ->add('bottomLines', TextareaType::class, ['required' => false, 'attr' => ['rows' => 7, 'placeholder' => 'poll.bottomlines.placeholder']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Poll::class]);
    }
}
