<?php

namespace Softspring\TranslatableBundle\Doctrine;

use Doctrine\ORM\Event\PostLoadEventArgs;
use Softspring\TranslatableBundle\Model\Translation;
use Symfony\Component\HttpFoundation\RequestStack;

class TranslationFieldListener
{
    public function __construct(protected RequestStack $requestStack)
    {
    }

    public function postLoad(PostLoadEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        $classMetadata = $eventArgs->getObjectManager()->getClassMetadata(get_class($entity));
        $entityReflection = $classMetadata->getReflectionClass();

        foreach ($classMetadata->fieldMappings as $fieldMapping) {
            // fieldMapping is an array in older doctrine versions, >= 3 is an object.
            $type = is_array($fieldMapping) ? $fieldMapping['type'] : $fieldMapping->type;
            $fieldName = is_array($fieldMapping) ? $fieldMapping['fieldName'] : $fieldMapping->fieldName;

            if ('sfs_translation' !== $type) {
                continue;
            }

            if ($entityReflection->hasMethod('get'.ucfirst($fieldName))) {
                $translation = $entity->{'get'.ucfirst($fieldName)}();
            } elseif ($entityReflection->hasProperty($fieldName) && $entityReflection->getProperty($fieldName)->isPublic()) {
                $translation = $entity->{$fieldName};
            } else {
                $translation = null;
            }

            if ($translation instanceof Translation) {
                $translation->__setRequestStack($this->requestStack);
            }
        }
    }
}
