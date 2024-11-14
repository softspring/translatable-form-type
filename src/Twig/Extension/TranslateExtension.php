<?php

namespace Softspring\TranslatableBundle\Twig\Extension;

use Softspring\TranslatableBundle\Model\Translation;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TranslateExtension extends AbstractExtension
{
    public function __construct(protected RequestStack $requestStack)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('sfs_translate', [$this, 'translate'], ['is_safe' => ['html']]),
        ];
    }

    public function translate(Translation $translation): string
    {
        return $translation->translate($this->requestStack->getCurrentRequest()->getLocale());
    }
}
