<?php

namespace Betalabs\Engine\Inbound;

use Betalabs\Engine\Exceptions\AttributesDoesNotExistException;
use Illuminate\Http\Request;

class InboundRequest extends Request
{
    /** @var array */
    protected $items = [];
    /** @var array|null */
    protected $quantities = [];
    /** @var string */
    protected $zip_code;
    /** @var array */
    protected $originalData;

    /**
     * InboundRequest constructor.
     *
     * @param array $originalData
     */
    public function __construct(array $originalData)
    {
        parent::__construct();
        $this->originalData = $originalData;
    }

    /**
     * Merge marketplace inbound request into laravel request for Engine Freight Calculator
     * You can override this method to do this according marketplace
     *
     * @return void
     * @throws AttributesDoesNotExistException
     */
    public function transformInboundRequest() {
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