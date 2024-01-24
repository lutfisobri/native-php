<?php
namespace Riyu\View\Widget;

class Screen
{
    protected $title;

    protected $lang;

    protected $meta = [];

    protected $favicon;

    protected $css = [];

    protected $script;

    protected $js = [];

    protected $style;

    protected $body;

    public function title(string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function lang(string $lang)
    {
        $this->lang = $lang;

        return $this;
    }

    public function meta(string $name, string $content)
    {
        $this->meta[] = [
            'name' => $name,
            'content' => $content
        ];

        return $this;
    }

    public function favicon(string $favicon)
    {
        $this->favicon = $favicon;

        return $this;
    }

    public function css(string $css, $attributes = [])
    {
        $this->css[] = [
            'url' => $css,
            'attributes' => $attributes
        ];

        return $this;
    }

    public function style(string $style)
    {
        $this->style .= $style;

        return $this;
    }

    public function js($url, $attributes = [])
    {
        $this->js[] = [
            'url' => $url,
            'attributes' => $attributes
        ];

        return $this;
    }

    public function script(string $script)
    {
        $this->script .= $script;

        return $this;
    }

    public function body(string $body)
    {
        $this->body .= $body;

        return $this;
    }

    public function render()
    {
        return $this->compile();
    }

    protected function compile()
    {
        $title = $this->renderTitle();
        $lang = $this->renderLang();
        $meta = $this->renderMeta() ?? $this->defaultMeta();
        $style = $this->renderStyle();
        $css = $this->renderCss();
        $script = $this->renderScript();
        $js = $this->renderJs();

        $content = <<<HTML
            <!DOCTYPE html>
            $lang
            <head>
                $title
                $meta
                $style
                $css
                $script
                $js
        HTML;

        $content .= <<<HTML
        </head>
        <body>
        HTML;

        $content .= $this->body;

        $content .= <<<HTML
        </body>
        </html>
        HTML;

        return $content;
    }

    protected function defaultMeta()
    {
        $content = <<<HTML
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        HTML;

        $content .= <<<HTML
            <meta charset="UTF-8">
        HTML;

        return $content;
    }

    protected function renderTitle()
    {
        $title = $this->title;

        if (empty($title)) {
            $title = 'Riyu';
        }

        $content = "<title>$title</title>";

        return $content;
    }

    protected function renderLang()
    {
        $lang = $this->lang;

        if (empty($lang)) {
            $lang = 'en';
        }

        $content = "<html lang=\"$lang\">";

        return $content;
    }

    protected function renderMeta()
    {
        $meta = $this->meta;

        if (empty($meta)) {
            return $this->defaultMeta();
        }

        $content = '';
        foreach ($meta as $value) {
            $name = $value['name'];
            $content = $value['content'];

            $content .= <<<HTML
            <meta name="$name" content="$content">
        HTML;
        }

        return $content;
    }

    protected function renderStyle()
    {
        $style = $this->style;

        if (empty($style)) {
            return null;
        }

        $content = "<style>$style</style>";

        return $content;
    }

    protected function renderCss()
    {
        $css = $this->css;

        if (empty($css)) {
            return '';
        }

        $content = '';
        foreach ($css as $value) {
            $url = $value['url'];
            $attr = '';
            foreach ($value['attributes'] as $key => $value) {
                $attr .= $key . '="' . $value . '" ';
            }

            $content .= <<<HTML
            <link rel="stylesheet" href="$url" $attr>
        HTML;
        }

        return $content;
    }

    protected function renderScript()
    {
        $script = $this->script;

        if (empty($script)) {
            return '';
        }

        $content = "<script>$script</script>";

        return $content;
    }

    protected function renderJs()
    {
        $js = $this->js;

        if (empty($js)) {
            return '';
        }

        $content = '';
        foreach ($js as $value) {
            $url = $value['url'];
            $attr = '';
            foreach ($value['attributes'] as $key => $value) {
                $attr .= $key . '="' . $value . '" ';
            }

            $content .= <<<HTML
            <script src="$url" $attr></script>
        HTML;
        }

        return $content;
    }
}