<?php

namespace App\View\Components;

use Illuminate\View\Component;

class modal extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $name;
    public $size;
    public function __construct($name = null, $size = null)
    {
        $this->name = $name;
        $this->size = $size ?? 'md';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
