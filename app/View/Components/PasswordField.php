<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PasswordField extends Component
{
    public $id;
    public $name;
    public $label;
    public $placeholder;
    public $icon;
    public $readonly;
    public $disabled;
    public $required;
    public $value;

    public function __construct($id, $name, $label, $placeholder = null, $icon = null, $readonly = false, $disabled = false, $required = false, $value = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->icon = $icon;
        $this->readonly = $readonly;
        $this->disabled = $disabled;
        $this->required = $required;
        $this->value = $value;
    }

    public function render(): View|Closure|string
    {
        return view('components.password-field');
    }
}
