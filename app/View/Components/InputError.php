<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputError extends Component
{
    public array $messages;

    public function __construct(array $messages = [])
    {
        $this->messages = $messages;
    }

    public function render(): View|Closure|string
    {
        return view('components.input-error');
    }
}
