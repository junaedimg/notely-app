<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Quadrant extends Component
{
    public function __construct(public string $quadrant) {} 

    // Colors are defined in resources/css/app.css (single source of truth)
    private static array $map = [
        'priority1' => ['label' => 'Do',        'icon' => 'priority_high',       'class' => 'p1'],
        'priority2' => ['label' => 'Schedule',   'icon' => 'calendar_month',     'class' => 'p2'],
        'priority3' => ['label' => 'Delegate',   'icon' => 'arrow_forward',      'class' => 'p3'],
        'priority4' => ['label' => 'Eliminate',  'icon' => 'remove_circle_outline', 'class' => 'p4'],
    ];

    public static function get(string $quadrant): array
    {
        return self::$map[$quadrant] ?? self::$map['priority4'];
    }

    public function render()
    {
        return view('components.quadrant');
    }
}
