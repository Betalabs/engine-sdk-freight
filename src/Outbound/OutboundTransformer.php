<?php

namespace Betalabs\Engine\Outbound;


use Betalabs\Engine\Contracts\AbstractTransformer;

abstract class OutboundTransformer extends AbstractTransformer
{
    public abstract function transform(\Betalabs\Engine\Contracts\ZipCodeRange $model);
}