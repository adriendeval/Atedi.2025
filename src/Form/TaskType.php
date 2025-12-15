<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\CallbackTransformer;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('price', MoneyType::class, [
                'currency' => false,
            ])
            ->add('color', ColorType::class)
        ;

        $builder->get('color')
            ->addModelTransformer(new CallbackTransformer(
                function ($colorAsHex) {
                    // transform the string from database to form
                    // database: "RRGGBB" -> form: "#RRGGBB"
                    return $colorAsHex ? '#' . $colorAsHex : '#000000';
                },
                function ($colorAsHexWithHash) {
                    // transform the string from form to database
                    // form: "#RRGGBB" -> database: "RRGGBB"
                    return $colorAsHexWithHash ? str_replace('#', '', $colorAsHexWithHash) : null;
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
