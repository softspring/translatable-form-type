<?php

namespace Softspring\TranslatableBundle\Form\Transformer;

use Softspring\TranslatableBundle\Model\Translation;
use Symfony\Component\Form\DataTransformerInterface;

class TranslationTransformer implements DataTransformerInterface
{
    public function __construct(protected array $options)
    {
    }

    public function transform(mixed $value): array
    {
        if (!$value) {
            $value = new Translation();
        } elseif (is_array($value)) {
            $value = Translation::createFromArray($value);
        }

        if (empty($value->getTransId())) {
            $value->setTransId(uniqid());
        }

        $value->setDefaultLocale($this->options['default_language']);

        return $value->__toArray();
    }

    public function reverseTransform(mixed $value): ?Translation
    {
        if (empty($value)) {
            return null;
        }

        return Translation::createFromArray($value);
    }
}
