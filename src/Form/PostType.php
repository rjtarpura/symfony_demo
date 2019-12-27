<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'attr' => [
                    'placeholder' => 'Title of post',
                    'class' => 'custom_class'
                ]
            ])
            ->add('description', TextareaType::class,[
                'attr'  =>  [
                    'placeholder' => 'Enter the description.',
                ]
            ])
            ->add('category',EntityType::class,[                
                'class' => 'App\Entity\Category',   //https://symfony.com/doc/current/reference/forms/types/entity.html
            ])
            ->add('save',SubmitType::class,[
                'attr'=>[
                    'class' => 'btn-primary'
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
