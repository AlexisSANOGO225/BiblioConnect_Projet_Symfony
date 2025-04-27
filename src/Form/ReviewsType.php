<?php

namespace App\Form;

use App\Entity\Reviews;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ReviewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', IntegerType::class, [
                'label' => 'Note (1 à 5)',
                'attr' => ['min' => 1, 'max' => 5, 'placeholder' => 'Notez ce livre'],
                'constraints' => [
                    new Assert\Range(['min' => 1, 'max' => 5]),
                ],
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Votre avis',
                'attr' => ['placeholder' => 'Entrez votre commentaire'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le commentaire ne peut pas être vide.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reviews::class,
        ]);
    }
}

