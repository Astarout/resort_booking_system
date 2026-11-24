<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GalleryModal extends Component
{
    public $show;
    public $image;
    public $description;
    public $closeAction;

    public function __construct($show, $image, $description, $closeAction)
    {
        $this->show = $show;
        $this->image = $image;
        $this->description = $description;
        $this->closeAction = $closeAction;
    }

    public function render(): View|Closure|string
    {
        return view('components.gallery-modal');
    }
}
