<?php
/*
 * Copyright 2014-2016 Arnaud Bienvenu
 *
 * This file is part of Kyela.

 * Kyela is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Kyela is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with Kyela.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Abienvenu\KyelaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\Extension\Core\Type\ChoiceType as BaseChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['attr' => ['autofocus' => 'autofocus', 'placeholder' => "choice.name.placeholder"]])
            ->add('value', null, ['attr' => ['placeholder' => 'choice.name.value']])
            ->add('color', BaseChoiceType::class, ['choices_as_values' => true, 'choices' => [
            	'green' => 'green',
	            'orange' => 'orange',
	            'red' => 'red',
	            'blue' => 'blue',
	            'cyan' => 'cyan',
	            'purple' => 'purple',
	            'gray' => 'gray',
			]])
            ->add('icon', IconType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'Abienvenu\KyelaBundle\Entity\Choice']);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'abienvenu_kyelabundle_choice';
    }
}
