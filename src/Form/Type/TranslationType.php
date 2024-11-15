<?php

namespace Softspring\TranslatableBundle\Form\Type;

use Softspring\TranslatableBundle\Form\Transformer\TranslationTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationType extends AbstractType
{
    public function getParent(): string
    {
        return TranslatableType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'translation';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'type' => TextType::class, // or TextareaType::class
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('_trans_id', HiddenType::class);

        $builder->resetModelTransformers();
        $builder->addModelTransformer(new TranslationTransformer($options));
    }
}
