<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class HelpersExtension extends AbstractExtension
{

    private $script = [];
    private $style = [];

    public function getFunctions()
    {
        return [
            new TwigFunction('asset', [$this, 'asset'], ['is_safe' => ['html']]),
            new TwigFunction('paginate', [$this, 'paginate'], ['is_safe' => ['html']]),
            new TwigFunction('widget', [$this, 'widget'], ['is_safe' => ['html']]),

            new TwigFunction('script', [$this, 'script'], ['is_safe' => ['html']]),
            new TwigFunction('printScript', [$this, 'printScript'], ['is_safe' => ['html']]),

            new TwigFunction('style', [$this, 'style'], ['is_safe' => ['html']]),
            new TwigFunction('printStyle', [$this, 'printStyle'], ['is_safe' => ['html']]),

        ];
    }

    public function widget($widgetClass, $params)
    {
        /** @var WidgetInterface $widget */
        $widget = new $widgetClass;
        foreach ($params as $paramName => $paramValue) {
            $widget->{$paramName} = $paramValue;
        }
        return $widget->render();
    }

    public function asset($path, $param = null)
    {
        return $this->assetsService->getAssetUrl($path, $param);
    }

    public function script($path, $attributes = [])
    {
        $this->script[] = [
            'path' => $path,
            'attributes' => $attributes,
        ];
    }

    public function printScript()
    {
        $code = '';
        foreach ($this->script as $script) {
            $code .= '<script src="'.$script['path'].'"></script>';
        }
        return $code;
    }

    public function style($path, $attributes = [])
    {
        $this->style[] = [
            'path' => $path,
            'attributes' => $attributes,
        ];
    }

    public function printStyle()
    {
        $code = '';
        foreach ($this->style as $style) {
            $code .= '<link href="'.$style['path'].'" rel="stylesheet">';
        }
        return $code;
    }

}