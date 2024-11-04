<?php

namespace Softspring\TranslatableBundle\Form\Guesser;

use Doctrine\ORM\Mapping\ClassMetadata;
use Softspring\TranslatableBundle\Form\Type\TranslationType;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmTypeGuesser;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Guess\ValueGuess;

class TranslatableTypeGuesser extends DoctrineOrmTypeGuesser implements FormTypeGuesserInterface
{
    public function guessType($class, $property): ?TypeGuess
    {
        if (!$ret = $this->getMetadata($class)) {
            return new TypeGuess('Symfony\Component\Form\Extension\Core\Type\TextType', [], Guess::LOW_CONFIDENCE);
        }

        /** @var ClassMetadata $metadata */
        list($metadata, $name) = $ret;

        if ('sfs_translation' == $metadata->getTypeOfField($property)) {
            return new TypeGuess(TranslationType::class, [], Guess::VERY_HIGH_CONFIDENCE);
        }

        return null;
    }

    public function guessRequired($class, $property): ?ValueGuess
    {
        return null;
    }

    public function guessMaxLength($class, $property): ?ValueGuess
    {
        return null;
    }

    public function guessPattern($class, $property): ?ValueGuess
    {
        return null;
    }
}
