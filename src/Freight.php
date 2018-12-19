<?php

namespace Betalabs\Engine;

use Betalabs\Engine\Contracts\ZipCodeRangeCalculator;
use Betalabs\Engine\Contracts\Item;
use Betalabs\Engine\Contracts\Channel;
use Betalabs\Engine\Helpers\ResponseFormatter;

abstract class Freight
{
    /** @var \Betalabs\Engine\Inbound\InboundRequest */
    protected $inboundRequest;
    /** @var \Betalabs\Engine\Outbound\OutboundTransformer */
    protected $outboundAdapter;

    /** App must implement Outbound Adapter */
    abstract function setOutboundAdapter();
    /** App must implement Inbound Request */
    abstract function setInboundRequest();

    /**
     * Return calculated freight according outbound and inbound response.
     *
     * @param array $originalData
     *
     * @return \Illuminate\Http\Response
     * @throws Exceptions\AttributesDoesNotExistException
     */
    public function calculate(array $originalData) {
        $calculator = resolve(ZipCodeRangeCalculator::class);
        $item = resolve(Item::class);
        $channel = resolve(Channel::class)::ECOMMERCE;
        $this->setOutboundAdapter();
        $this->setInboundRequest();
        $this->inboundRequest->setOriginalData($originalData);
        $this->inboundRequest->transformInboundRequest();

        $items = $item::whereIn('alias_id', $this->inboundRequest->input('items'))->get();
        $quantities = $this->inboundRequest->input('quantities');
        $zipCode = $this->inboundRequest->input('zip_code');

        $calculator
            ->setZipCode($zipCode)
            ->setItems($items)
            ->setChannels([$channel])
            ->setQuantities($quantities);

        if (null !== $this->outboundAdapter) {
            return $this->respond()->collection(
                $calculator->calculate(),
                $this->outboundAdapter
            );
        }

        return $this->respond()->noContent();
    }

    /**
     * Resolve ResponseFormatter
     *
     * @return mixed
     */
    private function respond(): ResponseFormatter
    {
        return new ResponseFormatter;
    }
}