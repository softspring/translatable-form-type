<?php

namespace Softspring\TranslatableBundle\Form\Type;

use Softspring\TranslatableBundle\Model\Translation;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationType extends TranslatableType
{
    //    public function getBlockPrefix(): string
    //    {
    //        return 'translation';
    //    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'type' => TextType::class, // or TextareaType::class
        ]);
    }

    /**
     * @param Translation|null $data
     */
    protected function transform(mixed $data, array $options): array
    {
        if (!$data) {
            $data = new Translation();
        }

        $data->setDefaultLocale($options['default_language']);

        return $data->__toArray();
    }

    protected function reverseTransform(array $data, array $options): ?Translation
    {
        if (empty($data)) {
            return null;
        }

        return Translation::createFromArray($data);
    }
}
