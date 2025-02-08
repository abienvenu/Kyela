<?php

namespace App\Form\Type;

use App\Entity\Choice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as SymfonyChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$colorChoices = [
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
		];

		$icons = [
			'check',
			'x',
			'hand-thumbs-up',
			'hand-thumbs-down',
			'check-circle',
			'question-circle',
			'info-circle',
			'basket',
			'cake',
			'cup-straw',
			'cup-hot',
			'music-note',
			'gift',
			'house',
			'clock',
			'lock',
			'flag',
			'camera',
			'book',
			'camera-video',
			'film',
			'pin-angle',
			'telephone',
			'telephone-outbound',
			'headphones',
			'chat-left-text',
			'megaphone',
			'volume-mute',
			'cart',
			'wrench',
			'briefcase',
			'paperclip',
			'envelope',
			'pencil',
			'person',
			'asterisk',
			'currency-euro',
			'eye',
			'signpost-split',
			'airplane',
			'send',
			'globe',
			'tree',
			'heart',
			'star-fill',
			'star',
			'lightning',
		];
		$iconChoices = [];
		foreach ($icons as $icon) {
			$iconChoices[$icon] = $icon;
		}

		$builder
			->add('name', null, ['attr' => ['autofocus' => 'autofocus', 'placeholder' => "choice.name.placeholder"]])
			->add('value', null, ['attr' => ['placeholder' => 'choice.name.value']])
			->add('color', SymfonyChoiceType::class, ['choices' => $colorChoices])
			->add('icon', SymfonyChoiceType::class, ['choices' => $iconChoices, 'expanded' => true, 'multiple' => false]
			);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults(['data_class' => Choice::class]);
	}
}
