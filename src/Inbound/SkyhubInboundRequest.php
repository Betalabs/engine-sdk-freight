<?php

namespace Betalabs\Engine\Inbound;


use Betalabs\Engine\Contracts\Item;

class SkyhubInboundRequest extends InboundRequest
{
    /** @var array */
    private $data;

    /**
     * SkyhubInboundRequest constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct();
        $this->data = $data;
        $this->transformInboundRequestFromMarketplace();
    }

    /**
     * Must transform inbound request from marketplace
     * and set items, quantities and zip_code for Engine.
     */
    protected function transformInboundRequestFromMarketplace(): void
    {
        $this->zip_code = $this->data['destinationZip'];

        foreach ($this->data['volumes'] as $item) {
            $itemId = $this->getItemBySku($item)->id;
            $quantity = $item['quantity'];

            array_push($this->items, $itemId);
            array_push($this->quantities, $quantity);
        }
    }

    /**
     * InboundRequest request style for marketplace
     *
     * @return array
     */
    public function getInboundRequestRules(): array
    {
        return [
            'destinationZip' => 'integer',
            'volumes' => 'array',
            'in:volumes' => [
                'sku' => 'string',
                'quantity' => 'integer',
                'price' => 'double',
                'height' => 'double',
                'length' => 'double',
                'width' => 'double',
                'weight' => 'double'
            ]
        ];
    }

    /**
     * Return item with the associated SKU
     *
     * @param array $item
     * @return \Betalabs\Engine\Contracts\Item
     */
    private function getItemBySku(array $item) {
        return
            Item::whereHas('identification', function ($itemIdentification) use ($item) {
                $itemIdentification->where('sku', $item['sku']);
            })->firstOrFail();
    }
}