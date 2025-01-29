<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as BaseChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IconType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
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

    public function getParent(): string
    {
        return BaseChoiceType::class;
    }
}
