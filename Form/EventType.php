<?php
/**
 * Copyright 2014 Arnaud Bienvenu
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

namespace Abienvenu\KyelaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['attr' => ['autofocus' => 'autofocus', 'placeholder' => 'date.name.placeholder']])
            ->add('place', null, ['required' => false, 'attr' => ['placeholder' => 'date.place.placeholder']])
            ->add('date', null, ['required' => false, 'widget' => 'single_text', 'format' => 'dd-MM-yyyy', 'attr' => ['class' => 'datepicker']])
            ->add('time', null, ['required' => false, 'widget' => 'single_text'])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Abienvenu\KyelaBundle\Entity\Event'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'abienvenu_kyelabundle_event';
    }
}
