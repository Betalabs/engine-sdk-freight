<?php

namespace Betalabs\Engine\Inbound;

use Betalabs\Engine\Exceptions\AttributesDoesNotExistException;
use Illuminate\Http\Request;

abstract class InboundRequest extends Request
{
    /** @var array */
    protected $items = [];
    /** @var array|null */
    protected $quantities = [];
    /** @var string */
    protected $zip_code;

    /**
     * Must transform inbound request from marketplace
     * and set items, quantities and zip_code for Engine.
     *
     * @return void
     */
    protected abstract function transformInboundRequestFromMarketplace(): void;

    /**
     * InboundRequest request style for marketplace
     *
     * @return array
     */
    public abstract function getInboundRequestRules(): array;

    /**
     * Merge marketplace inbound request into laravel request for Engine Freight Calculator
     *
     * @return void
     * @throws \Betalabs\Engine\Exceptions\AttributesDoesNotExistException
     */
    public function mergeInboundRequest() {
        if (!isset($this->items, $this->quantities, $this->zip_code)) {
            throw new AttributesDoesNotExistException(
                'Need to set items, quantities and zip_code for Engine Freight Calculator'
            );
        }

        parent::merge([
            'items' => $this->items,
            'quantities' => $this->quantities,
            'zip_code' => $this->zip_code
        ]);
    }
}