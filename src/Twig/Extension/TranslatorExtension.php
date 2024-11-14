<?php

namespace Softspring\TranslatableBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class TranslatorExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(protected ?string $apiDriver = null)
    {
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
}
