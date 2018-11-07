<?php

namespace Betalabs\Engine\Outbound;

use League\Fractal\TransformerAbstract;

abstract class OutboundTransformer extends TransformerAbstract
{
    public abstract function transform(\Betalabs\Engine\Contracts\ZipCodeRange $model);
}