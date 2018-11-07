<?php

namespace Betalabs\Engine\Helpers\Serializers;

use League\Fractal\Serializer\DataArraySerializer;

class DataArrayComputed extends DataArraySerializer
{

    /**
     * Serialize the meta.
     *
     * @param array $meta
     *
     * @return array
     */
    public function meta(array $meta)
    {
        if (empty($meta)) {
            return [];
        }

        return array_filter($meta, function($each) {
            return !empty($each);
        });
    }

}