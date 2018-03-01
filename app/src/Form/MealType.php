<?php

namespace App\Form;

use App\Entity\Meal;
use App\Form\Type\DateTimePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MealType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class, [
                'attr' => ['autofocus' => true],
                'label' => 'Meal text',
                'required' => true
            ])
            ->add('caloriesNumber', null, [
                'label' => 'Calories',
                'required' => true,
                'attr' => ['min' => 0],
            ])
            ->add('createdAt', DateTimePickerType::class, [
                'label' => 'Created At',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'required' => true
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Meal::class,
        ]);
    }
}
