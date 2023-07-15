<?php

namespace App\Form;

use App\Entity\Note;
use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\DependencyInjection\Loader\Configurator\security;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class NotesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		$user = new User();
        $builder
            ->add('title',TypeTextType::class,[
                "attr"=>[
                    'class'=>'form-control form-control-md border-success mb-2'
                ]])
            ->add('text',TextareaType::class,[
                "attr"=>[
                    'class'=>'form-control form-control-md border-success mb-2'
                ]])
           ->add('idUser',EntityType::class,['class'=>User::class,'choice_label' => "id",'attr'=>['class'=>'form-control d-none'],'label'=>false])
           ->add('image',FileType::class,[
            'label'=>'image',
            'multiple'=>true,
            'mapped'=>false,
            'required'=>false,
            'attr'=>['class'=>'form-control','accept' => '.png, .jpg, .jpeg',],
           ])
           ->add('audio',FileType::class,[
            'label'=>'audio',
            'multiple'=>true,
            'mapped'=>false,
            'required'=>false,
            'attr'=>['class'=>'form-control', 'accept' => '.mp3, .wav, .ogg',],
           ])
           ->add('video',FileType::class,[
            'label'=>'video',
            'multiple'=>true,
            'mapped'=>false,
            'required'=>false,
            'attr'=>['class'=>'form-control','accept' => '.mp4, .mov, .avi',],
           ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
