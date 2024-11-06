<?php

namespace Softspring\TranslatableBundle\Twig\Extension;

use Symfony\Component\HttpFoundation\Request;
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

    public function translate(mixed $translatableText): string
    {
        return self::translateWithRequest($translatableText, $this->requestStack->getCurrentRequest());
    }

    public static function translateWithRequest(mixed $translatableText, Request $request): string
    {
        if (!is_array($translatableText)) {
            return '';
        }

        // TODO allow empty locale values with some metadata flag to avoid fallback to default locale

        if (!empty($translatableText[$request->getLocale()])) {
            return $translatableText[$request->getLocale()];
        }

        if (!empty($translatableText[$translatableText['_default'] ?? null])) {
            return $translatableText[$translatableText['_default'] ?? null];
        }

        if (!empty($translatableText[$request->getDefaultLocale()])) {
            return $translatableText[$request->getDefaultLocale()];
        }

        return '';
    }
}
