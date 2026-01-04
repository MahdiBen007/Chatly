<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Sidebar extends Component
{
    /**
     * The users.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $users;

    /**
     * Create a new component instance.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $users
     * @return void
     */
    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('components.sidebar');
    }
}
