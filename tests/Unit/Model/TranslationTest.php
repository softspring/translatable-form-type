<?php

namespace Softspring\TranslatableBundle\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Softspring\TranslatableBundle\Model\Translation;

class TranslationTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $translation = new Translation();
        $translation->setDefaultLocale('en');
        $translation->setTranslation('en', 'Hello');
        $translation->setTranslation('es', 'Hola');
        $translation->setTranslation('fr', 'Bonjour');
        $translation->setTranslation('it', 'Ciao');

        $encoded = json_encode($translation);
        $this->assertEquals('{"en":"Hello","es":"Hola","fr":"Bonjour","it":"Ciao","_trans_id":null,"_default":"en"}', $encoded);
    }
}