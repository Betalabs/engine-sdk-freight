<?php

namespace Betalabs\Engine\Outbound;

use Betalabs\Engine\Contracts\ZipCodeRange;

class SkyhubOutbound extends OutboundTransformer
{
    public function transform(ZipCodeRange $zipCodeRange)
    {
        $transit = explode(".", $zipCodeRange->time_cost)[0];
        $expedition = 0;

        $data = [
            'shippingQuotes' => [
                [
                    'shippingCost' => $zipCodeRange->amount,
                    'deliveryTime' => [
                        'total' => $transit + $expedition,
                        'transit' => $transit,
                        'expedition' => $expedition
                    ],
                    'shippingMethodId' => $zipCodeRange->correios_service_code,
                    'shippingMethodName' => $zipCodeRange->description,
                    'shippingMethodDisplayName' => $zipCodeRange->description
                ]
            ]
        ];

        return $this->adaptDefaultTransformWithSelectedFields($data);
    }
}