<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputGroupLabel extends Component
{
    public $labelName;
    public $name;
    public $type;
    public $typeInput;
    public $colLabel;
    public $ColInput;
    public $value;
    public $placeholder;
    public $route;

    public function __construct($config = null)
    {
        if ($config['type'] == 'text' || $config['type'] == 'number') {
            if ($config['value'] != '')
                $this->typeInput = 'readonly';
        }
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
        $this->labelName = \Illuminate\Support\Str::ucfirst($config['name']);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input-group-label');
    }
}
