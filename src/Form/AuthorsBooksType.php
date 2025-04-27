<?php

namespace App\Form;

use App\Entity\Authors;
use App\Entity\AuthorsBooks;
use App\Entity\Books;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorsBooksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('authors', EntityType::class, [
                'class' => Authors::class,
                'choice_label' => 'id',
            ])
            ->add('books', EntityType::class, [
                'class' => Books::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AuthorsBooks::class,
        ]);
    }
}
