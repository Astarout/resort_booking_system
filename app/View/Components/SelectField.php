<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectField extends Component
{
    public $label;
    public $id;
    public $name;
    public $type;
    public $value;
    public $icon;
    public $placeholder;
    public $readonly;

    public function __construct($label, $name, $type = "text", $id = null, $value = null, $icon = null, $placeholder = null, $readonly = false)
    {
        $this->label = $label;
        $this->id = $id ?? $name;
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->icon = $icon;
        $this->placeholder = $placeholder ?? " ";
        $this->readonly = $readonly;
    }

    public function render(): View|Closure|string
    {
        return view('components.select-field');
    }
}
