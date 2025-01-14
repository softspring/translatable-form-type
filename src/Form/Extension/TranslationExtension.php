<?php

namespace Softspring\TranslatableBundle\Form\Extension;

use Softspring\TranslatableBundle\Form\Type\TranslationType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Routing\RouterInterface;

class TranslationExtension extends AbstractTypeExtension
{
    public function __construct(
        protected RouterInterface $router,
        protected bool $apiEnabled,
        protected ?string $apiDriver = null,
    ) {
    }

    public static function getExtendedTypes(): iterable
    {
        return [TranslationType::class];
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        if (!$this->apiDriver) {
            return;
        }

        foreach ($view->children as $locale => $field) {
            if (str_starts_with($locale, '_')) {
                continue;
            }

            $fallback = $view->children['_default']->vars['value'] ?? 'en';

            if ($locale === $fallback) {
                continue;
            }

            $fallbackField = $view->children[$fallback] ?? null;

            $field->vars['translate_prepend_button'] = [
                'attr' => [
                    'data-translate' => '',
                    'data-translate-source-field' => $fallbackField->vars['full_name'] ?? '',
                    'data-translate-target-field' => $field->vars['full_name'],
                    'data-translate-source-locale' => $fallback,
                    'data-translate-target-locale' => $locale,
                    'data-translate-url' => $this->router->generate('sfs_translatable_api_translate'),
                ],
                'api_driver' => $this->apiDriver,
                'enabled' => null !== $fallbackField,
            ];

            $field->vars['block_prefixes'][] = 'translation_element_widget_with_api';
        }
    }
}
