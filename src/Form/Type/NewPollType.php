<?php

namespace App\Form\Type;

use App\Entity\Poll;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewPollType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults(['data_class' => Poll::class]);
	}
}
