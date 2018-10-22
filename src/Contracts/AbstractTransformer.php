<?php

namespace Betalabs\Engine\Contracts;


class AbstractTransformer
{
    protected function adaptDefaultTransformWithSelectedFields($data = []) {
        return [];
    }
}