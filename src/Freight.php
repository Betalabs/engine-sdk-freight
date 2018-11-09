<?php

namespace Betalabs\Engine;

use Betalabs\Engine\Contracts\ZipCodeRangeCalculator;
use Betalabs\Engine\Helpers\ResponseFormatter;
use Illuminate\Http\Request;

abstract class Freight
{
    /** @var \Betalabs\Engine\Inbound\InboundRequest */
    protected $inboundRequest;
    /** @var \Betalabs\Engine\Outbound\OutboundTransformer */
    protected $outboundAdapter;

    /** App must implement Outbound Adapter */
    abstract function setOutboundAdapter();
    /** App must implement Inbound Adapter */
    abstract function setInboundRequest();

    /**
     * Return calculated freight according outbound and inbound response.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws Exceptions\AttributesDoesNotExistException
     */
    public function calculate(
        Request $request
    ) {
        $calculator = resolve(ZipCodeRangeCalculator::class);
        $this->setOutboundAdapter();
        $this->setInboundRequest();

        $this->inboundRequest->transformInboundRequest();

        $items = $this->inboundRequest->input('items');
        $quantities = $this->inboundRequest->input('quantities');
        $zipCode = $this->inboundRequest->input('zip_code');

        $calculator
            ->setZipCode($zipCode)
            ->setItemsIds($items)
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