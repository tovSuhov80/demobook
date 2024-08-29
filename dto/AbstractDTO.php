<?php

namespace app\dto;

use JsonSerializable;

abstract class AbstractDTO implements JsonSerializable
{
    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);
        foreach($vars as $name => $value){
            if ($value instanceof JsonSerializable) {
                $vars[$name] = $value->jsonSerialize();
            }
        }

        return $vars;
    }
}