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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as BaseChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IconType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $icons = [
              'ok', 'remove',
               'thumbs-up', 'thumbs-down',
               'ok-sign', 'question-sign', 'info-sign',
               'glass', 'cutlery', 'music', 'gift',
               'home', 'time', 'lock', 'flag', 'camera', 'book', 'facetime-video', 'film',
               'pushpin', 'phone', 'phone-alt', 'earphone', 'comment', 'bullhorn', 'volume-off',
               'shopping-cart', 'wrench', 'header', 'briefcase', 'paperclip',
               'envelope', 'pencil', 'user', 'asterisk', 'euro',
               'eye-open', 'road', 'plane', 'send', 'globe', 'tree-conifer', 'tree-deciduous',
               'heart', 'star', 'star-empty', 'flash',
           ];
        $choices = [];
        foreach ($icons as $icon)
        {
            $choices[$icon] = $icon;
        }

        $resolver->setDefaults([
            'choices' => $choices,
            'expanded' => true,
            'required' => false,
        ]);
    }

    public function getParent()
    {
        return BaseChoiceType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'abienvenu_kyelabundle_icon';
    }
}
