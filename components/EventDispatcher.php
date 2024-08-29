<?php

namespace app\components;

use yii\base\Component;

class EventDispatcher extends Component
{
    public function dispatch($event): void
    {
        $this->trigger($event::class, $event);
    }
}
