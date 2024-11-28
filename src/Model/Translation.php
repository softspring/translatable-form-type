<?php

namespace Softspring\TranslatableBundle\Model;

use ArrayAccess;
use Iterator;
use JsonSerializable;
use Stringable;
use Symfony\Component\HttpFoundation\RequestStack;

class Translation implements ArrayAccess, Stringable, JsonSerializable, Iterator
{
    protected ?string $transId = null;

    protected array $translations = [];

    protected string $defaultLocale = 'en';

    protected array $metadata = [];

    /**
     * Implement serialization to avoid storing RequestStack.
     */
    public function __serialize(): array
    {
        return $this->__toArray();
    }

    /**
     * Implement serialization to avoid storing RequestStack.
     */
    public function __unserialize(array $data): void
    {
        // retrieve default locale
        $this->defaultLocale = $data['_default'] ?? 'en';
        unset($data['_default']);

        // retrieve trans id
        if (isset($data['_trans_id'])) {
            $this->transId = $data['_trans_id'];
            unset($data['_trans_id']);
        } else {
            $this->transId = uniqid();
        }

        // retrieve metadata
        if (isset($data['_metadata'])) {
            $this->metadata = $data['_metadata'];
            unset($data['_metadata']);
        }

        // store translations (remaining fields)
        $this->translations = $data;
    }

    /**
     * RequestStack is initialized by TranslationFieldListener doctrine listener onPostLoad.
     */
    private ?RequestStack $requestStack = null;

    /**
     * @internal RequestStack is initialized by TranslationFieldListener doctrine listener onPostLoad
     */
    public function __setRequestStack(?RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
    }

    public static function createFromArray(array $data): self
    {
        $object = new self();
        $object->__unserialize($data);

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
        ], !empty($this->metadata) ? ['_metadata' => $this->metadata] : []);
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
        // get locale from current request if no locale specified
        if (null === $locale && $this->requestStack) {
            $locale = $this->requestStack->getCurrentRequest()->getLocale();
        }

        // if keep empty flag is set for this locale, return empty string
        // TODO STORE METADADA, AND ALLOW KEEP EMPTY VALUE
        // if (isset($this->metadata[$locale]['keep_empty'])) {
        //     return '';
        // }

        // try to get locale translation
        if (!empty($this->translations[$locale])) {
            return $this->translations[$locale];
        }

        // try to get fallback translation
        if (!empty($this->translations[$this->defaultLocale])) {
            return $this->translations[$this->defaultLocale];
        }

        // no translation
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

    public function jsonSerialize(): mixed
    {
        return $this->__toArray();
    }

    public function current(): mixed
    {
        return current($this->translations);
    }

    public function next(): void
    {
        next($this->translations);
    }

    public function key(): mixed
    {
        return key($this->translations);
    }

    public function valid(): bool
    {
        return null !== key($this->translations);
    }

    public function rewind(): void
    {
        reset($this->translations);
    }
}
