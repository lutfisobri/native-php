<?php

namespace views\components;

use Riyu\View\Component\Component;
use Riyu\View\Component\Div;
use Riyu\View\Widget\Screen;

class Template
{
    /**
     * Screen
     * 
     * @var Screen
     */
    private $screen;

    /**
     * Create a new template instance
     * 
     * @param Component|string $content
     * @param string $title
     * @return string
     */
    public function __construct($content, string $title)
    {
        $this->screen = $this->buildScreen($content, $title);
    }

    /**
     * Render content
     * 
     * @param Component|string $content
     * @return string
     */
    private function renderContent($content)
    {
        return (new Div())
            ->class('content')
            ->render($content);
    }

    public function render()
    {
        return $this->screen->render();
    }

    /**
     * Build screen
     * 
     * @param Component|string $content
     * @param string $title
     * @return Screen
     */
    private function buildScreen($content, string $title)
    {
        return (new Screen())
            ->title($title)
            ->favicon('/favicon.ico')
            ->css('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css')
            ->js('https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js', [
                'integrity' => 'sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r',
                'crossorigin' => 'anonymous'
            ])
            ->js('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js', [
                'integrity' => 'sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+',
                'crossorigin' => 'anonymous'
            ])
            ->body($this->renderContent($content));
    }
}
