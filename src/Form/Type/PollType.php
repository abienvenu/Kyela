<?php

namespace App\Form\Type;

use App\Entity\Poll;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PollType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('url', TextType::class, ['attr' => ['class' => 'form-control']])
			->add('title', TextType::class, ['attr' => ['autofocus' => 'autofocus', 'class' => 'form-control']])
			->add(
				'headLines',
				TextareaType::class,
				[
					'required' => false,
					'attr' => ['rows' => 7, 'placeholder' => 'poll.headlines.placeholder', 'class' => 'form-control'],
				]
			)
			->add(
				'bottomLines',
				TextareaType::class,
				[
					'required' => false,
					'attr' => ['rows' => 7, 'placeholder' => 'poll.bottomlines.placeholder', 'class' => 'form-control'],
				]
			);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults(['data_class' => Poll::class]);
	}
}
