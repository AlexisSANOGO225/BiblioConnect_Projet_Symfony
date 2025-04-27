<?php

namespace App\Form;

use App\Entity\Books;
use App\Entity\BooksInventory;
use App\Entity\Languages;
use App\Entity\LibraryStock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooksInventoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('availableCopie')
            ->add('price')
            ->add('librarystock', EntityType::class, [
                'class' => LibraryStock::class,
                'choice_label' => 'id',
            ])
            ->add('languages', EntityType::class, [
                'class' => Languages::class,
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
            'data_class' => BooksInventory::class,
        ]);
    }
}
