<?php

namespace Softspring\TranslatableBundle\Twig\Extension;

use Softspring\TranslatableBundle\Model\Translation;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class TranslatorExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(protected RequestStack $requestStack, protected ?string $apiDriver = null)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('sfs_translate', [$this, 'translate'], ['is_safe' => ['html']]),
        ];
    }

    public function getGlobals(): array
    {
        return [
            'sfs_translatable' => [
                'api' => [
                    'driver' => $this->apiDriver,
                ],
            ],
        ];
    }

    public function translate(Translation $translation): string
    {
        return $translation->translate($this->requestStack->getCurrentRequest()->getLocale());
    }
}
