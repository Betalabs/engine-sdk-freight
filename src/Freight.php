<?php

namespace Betalabs\Engine;

use Betalabs\Engine\Helpers\ResponseFormatter;
use Betalabs\Engine\Inbound\InboundRequest;
use Betalabs\Engine\Outbound\OutboundTransformer;

abstract class Freight
{
    /** @var \Betalabs\Engine\Contracts\ZipCodeRangeCalculator */
    private $calculator;
    /** @var \Betalabs\Engine\Inbound\InboundRequest */
    private $inboundRequest;
    /** @var \Betalabs\Engine\Outbound\OutboundTransformer */
    private $outboundAdapter;
    /** @var \Betalabs\Engine\Contracts\ZipCodeRangeTransformer */
    private $zipCodeRangeTransformer;

    /**
     * Freight constructor.
     *
     * @param \Betalabs\Engine\Inbound\InboundRequest $inboundRequest
     * @param \Betalabs\Engine\Contracts\ZipCodeRangeCalculator $calculator
     * @param \Betalabs\Engine\Contracts\ZipCodeRangeTransformer $zipCodeRangeTransformer
     */
    public function __construct(
        InboundRequest $inboundRequest,
        $calculator,
        $zipCodeRangeTransformer
    ) {
        $this->calculator = $calculator;
        $this->zipCodeRangeTransformer = $zipCodeRangeTransformer;
        $this->inboundRequest = $inboundRequest;
    }

    /**
     * Return calculated freight according outbound and inbound response.
     *
     * @return \Illuminate\Http\Response
     * @throws \Betalabs\Engine\Exceptions\AttributesDoesNotExistException
     */
    public function calculateFreight() {
        $this->inboundRequest->mergeInboundRequest();

        $items = $this->inboundRequest->input('items');
        $quantities = $this->inboundRequest->input('quantities');
        $zipCode = $this->inboundRequest->input('zip_code');

        $this->calculator
            ->setZipCode($zipCode)
            ->setItemsIds($items)
            ->setQuantities($quantities);

        if (null !== $this->outboundAdapter) {
            return $this->respond()->collection(
                $this->calculator->calculate(),
                $this->outboundAdapter
            );
        }
        return $this->respond()->collection(
            $this->calculator->calculate(),
            $this->zipCodeRangeTransformer->setCalculated(true)
        );
    }

    /**
     * Set outbound response adapter for calculated freight.
     * If not set, will use default response.
     *
     * @param \Betalabs\Engine\Outbound\OutboundTransformer $outboundAdapter
     */
    public function setOutboundAdapter(OutboundTransformer $outboundAdapter) {
        $this->outboundAdapter = $outboundAdapter;
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