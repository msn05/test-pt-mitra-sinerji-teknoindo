<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormInput extends Component
{
    public $name;
    public $type;
    public $icon;
    public $size;
    public $placeholder;
    public $params;
    public $route;
    public $global;
    public $value;

    public function __construct($config, $params = null)
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
        $this->params = $params ?? null;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */

    public function render()
    {
        return view('components.form-input');
    }
}
