<?php

namespace Softspring\TranslatableBundle\Model;

use ArrayAccess;

class Translation implements ArrayAccess
{
    protected ?string $transId = null;

    protected array $translations = [];

    protected string $defaultLocale = 'en';

    public static function createFromArray(array $data): self
    {
        $object = new self();

        // store default locale
        $object->defaultLocale = $data['_default'] ?? 'en';
        unset($data['_default']);

        // store trans id
        if (isset($data['_trans_id'])) {
            $object->transId = $data['_trans_id'];
            unset($data['_trans_id']);
        } else {
            $object->transId = uniqid();
        }

        $object->translations = $data;

        return $object;
    }

    public function __toString(): string
    {
        return $this->translate();
    }

    public function __toArray(): array
    {
        return array_merge($this->translations, [
            '_trans_id' => $this->transId,
            '_default' => $this->defaultLocale,
        ]);
    }

    public function getTransId(): ?string
    {
        return $this->transId;
    }

    public function setTransId(?string $transId): void
    {
        $this->transId = $transId;
    }

    public function getTranslations(): array
    {
        return $this->translations;
    }

    public function setTranslations(array $translations): void
    {
        $this->translations = $translations;
    }

    public function setTranslation(?string $locale, string $translation): void
    {
        $this->translations[$locale ?? $this->getDefaultLocale()] = $translation;
    }

    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }

    public function setDefaultLocale(string $defaultLocale): void
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function translate(?string $locale = null): string
    {
        if (!empty($this->translations[$locale])) {
            return $this->translations[$locale];
        }

        // TODO STORE METADADA, AND ALLOW KEEP EMPTY VALUE
        // if (isset($this->metadata[$locale]['keep_empty'])) {
        //     return '';
        // }

        if (!empty($this->translations[$this->defaultLocale])) {
            return $this->translations[$this->defaultLocale];
        }

        return '';
    }

    public function offsetExists($offset): bool
    {
        return true;
    }

    public function offsetGet($offset): mixed
    {
        return $this->translations[$offset] ?? '';
    }

    public function offsetSet($offset, $value): void
    {
        $this->translations[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->translations[$offset]);
    }
}
