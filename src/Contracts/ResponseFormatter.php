<?php

namespace Betalabs\Engine\Contracts;

use Illuminate\Support\Collection;

interface ResponseFormatter
{
    public function collection(Collection $collection, AbstractTransformer $transformer);
}