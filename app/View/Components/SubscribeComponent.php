<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SubscribeComponent extends Component
{
    public $subscribeContent;
    public function __construct($subscribeContent)
    {
        $this->subscribeContent = $subscribeContent;
    }

    
    public function render()
    {
        return view(theme('components.subscribe-component'));
    }
}
