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
			'cart4',
			'shop',
			'house',
			'clock',
			'lock',
			'flag',
			'pin-angle',
			'paperclip',
			'briefcase',
			'envelope',
			'pencil',
			'book',
			'camera',
			'camera-video',
			'film',
			'telephone',
			'headphones',
			'chat-left-text',
			'megaphone',
			'volume-mute',
			'wrench',
			'key',
			'person',
			'people',
			'prescription2',
			'heart-pulse',
			'thermometer-half',
			'hospital',
			'incognito',
			'signpost-split',
			'fuel-pump',
			'car-front',
			'bus-front',
			'train-front',
			'airplane',
			'rocket-takeoff',
			'send',
			'geo-alt',
			'globe',
			'tree',
			'heart',
			'trophy',
			'asterisk',
			'recycle',
			'currency-euro',
			'eye',
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
