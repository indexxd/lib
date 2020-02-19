<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('originalTitle')
            ->add('author')
            ->add('description')
            ->add('released')
            ->add('cover', FileType::class, [
                'mapped' => false, 
                "required" => false,
                "constraints" => [
                    new File([
                        "mimeTypes" => [
                            "image/jpeg",
                            "image/png",
                        ],
                        "mimeTypesMessage" => "File must be png/jpg"
                    ])
                ] 
            ])
            ->add('currentQuantity')
            ->add('submit', SubmitType::class, ["label" => "Update"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
