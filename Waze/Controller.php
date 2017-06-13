<?php

namespace Waze;

use Waze\Config;

class Controller
{
    protected $params;
    private $layout;
    private $layout_params;

    public function __construct($params = array())
    {
        $this->params = $params;
        $this->layout = 'work';
        $this->layout_params = array(
            'title' => 'check.waze - сканер ошибок карты',
            'css' => [
                '/builds/bootstrap.min.css' => 'media="all" data-turbolinks-track="true"',
            ],
            'navigation' => Config::get('navigation'),
            'js' => [],
            'flag' => null,
        );
        if (isset($this->params['flag'])) {
            $this->layout(['flag' => $this->params['flag']]);
            unset($this->params['flag']);
        }
    }

    public function process()
    {
        return $this->render(null, $this->params);
    }

    public function render($name, $params = array())
    {
        $__file_name = '../render/' . $name . '.phtml';
        if ($name === null || !file_exists($__file_name)) {
            return json_encode($params);
        }
        ob_start();
        extract($params);
        /** @noinspection PhpIncludeInspection */
        include($__file_name);
        $render = ob_get_contents();
        ob_end_clean();
        if ($this->layout) {
            $__layout_name = $this->layout;
            $__file_name = 'layout/' . $__layout_name;
            $this->layout = null;
            $render = $this->render($__file_name, $this->layout_params + array('content' => $render));
            $this->layout = $__layout_name;
        }
        return $render;
    }

    /**
     * @param array|string|null $name
     * @param array             $params
     */
    public function layout($name, $params = array())
    {
        if ($name === null) {
            $this->layout = null;
        } else if (is_array($name)) {
            $this->layout_params = array_replace_recursive($this->layout_params, $name);
        } else {
            $this->layout = $name;
            $this->layout_params = array_replace_recursive($this->layout_params, $params);
        }
    }
}