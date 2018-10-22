<?php

namespace Betalabs\Engine\Contracts;


abstract class AbstractTransformer
{
    protected function adaptDefaultTransformWithSelectedFields($data = []) {
        return [];
    }
}