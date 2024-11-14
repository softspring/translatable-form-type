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
            if ('sfs_translation' !== $fieldMapping->type) {
                continue;
            }

            if ($entityReflection->hasMethod('get'.ucfirst($fieldMapping->fieldName))) {
                $translation = $entity->{'get'.ucfirst($fieldMapping->fieldName)}();
            } elseif ($entityReflection->hasProperty($fieldMapping->fieldName) && $entityReflection->getProperty($fieldMapping->fieldName)->isPublic()) {
                $translation = $entity->{$fieldMapping->fieldName};
            } else {
                $translation = null;
            }

            if ($translation instanceof Translation) {
                $translation->__setRequestStack($this->requestStack);
            }
        }
    }
}
