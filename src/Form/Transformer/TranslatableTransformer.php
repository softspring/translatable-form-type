<?php

namespace Softspring\TranslatableBundle\Form\Transformer;

use Softspring\TranslatableBundle\Model\Translation;
use Symfony\Component\Form\DataTransformerInterface;

class TranslatableTransformer implements DataTransformerInterface
{
    public function __construct(protected array $options)
    {
    }

    public function transform(mixed $value): array
    {
        if (empty($value['_default'])) {
            $value['_default'] = $this->options['default_language'];
        }

        return $value;
    }

    public function reverseTransform(mixed $value): mixed
    {
        if (empty($value['_default'])) {
            $value['_default'] = $this->options['default_language'];
        }

        return $value;
    }
}
