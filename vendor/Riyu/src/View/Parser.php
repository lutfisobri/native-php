<?php
namespace Riyu\View;

class Parser
{
    protected $content;

    protected View $view;

    public function __construct($view, $content)
    {
        $this->view = $view;
        $this->content = $content;
    }

    public function parse()
    {
        $this->compile();

        return $this->content;
    }
    
    protected function compile()
    {
        $this->parseData();
    }

    protected function parseData()
    {
        $pattern = '/\{\{(.*)\}\}/U';
        $content = preg_replace_callback($pattern, function ($matches) {
            $matches[1] = trim($matches[1]);
            $matches[1] = '$this->' . $matches[1];

            return '<?php echo ' . $matches[1] . '; ?>';
        }, $this->content);

        $this->content = $content;
    }
}