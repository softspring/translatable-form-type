<?php

namespace Softspring\TranslatableBundle\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;
use RuntimeException;
use Softspring\TranslatableBundle\Model\Translation;

/**
 * Search examples:
 *      translated_name->"$.es" LIKE '%text%' AND translated_name->"$._default" = "en"
 *      JSON_CONTAINS(translated_name, '"text"', '$.es');
 */
class TranslationType extends JsonType
{
    public function getName(): string
    {
        return 'sfs_translation';
    }

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (!$value instanceof Translation) {
            throw new RuntimeException(sprintf('Expected %s class, but %s instance received', Translation::class, get_class($value)));
        }

        return parent::convertToDatabaseValue($value->__toArray(), $platform);
    }

    /**
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        $data = parent::convertToPHPValue($value, $platform);

        if (empty($data)) {
            return new Translation();
        }

        return Translation::createFromArray($data);
    }
}
