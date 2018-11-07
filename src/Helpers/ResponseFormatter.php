<?php

namespace Betalabs\Engine\Helpers;

use Betalabs\Engine\Helpers\Serializers\DataArrayComputed;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection as FactalCollection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class ResponseFormatter extends Response
{

    /** @var \League\Fractal\Manager */
    protected $manager;

    /** @var  \League\Fractal\Resource\ResourceAbstract */
    protected $resource;

    /** @var array */
    private $meta = [];

    /** @var array */
    private $computed = [];

    protected $originalContent;

    /**
     * @return mixed
     */
    public function getOriginalContent()
    {
        return $this->originalContent;
    }

    /**
     * ResponseFormatter constructor.
     * @param string $content
     * @param int $status
     * @param array $headers
     * @param \League\Fractal\Manager $manager
     */
    public function __construct(
        $content = '',
        $status = 200,
        $headers = array(),
        Manager $manager = null
    ) {

        parent::__construct($content, $status, $headers);

        if($this->manager = $manager) {
            $this->manager->setSerializer(new DataArrayComputed());
        }

    }

    /**
     * Transform a collection
     *
     * @param \Illuminate\Support\Collection $collection
     * @param \League\Fractal\TransformerAbstract
     * @return $this
     */
    public function collection(
        Collection $collection,
        TransformerAbstract $transformer
    ) {
        $this->originalContent = $collection;
        $this->resource = new FactalCollection($collection, $transformer);
        $this->buildContent();

        return $this;

    }

    /**
     * Transform a single item
     *
     * @param $item
     * @param \League\Fractal\TransformerAbstract $transformer
     * @return $this
     */
    public function item(
        $item,
        TransformerAbstract $transformer
    ) {

        $this->originalContent = $item;
        $this->resource = new Item($item, $transformer);
        $this->buildContent();

        return $this;

    }

    /**
     * Respond with array
     *
     * @param array $array
     * @return $this
     */
    public function array(array $array)
    {
        $this->originalContent = $array;
        $this->setContent($array);

        return $this;

    }

    /**
     * Respond an error
     *
     * @param $message
     * @param $code
     * @return $this
     */
    public function error($message, $code)
    {

        $this->originalContent = $message;
        $this->setContent($message);
        $this->statusCode($code);

        return $this;
    }

    /**
     * Set status code to 201
     *
     * @return $this
     */
    public function created()
    {
        $this->setStatusCode(201);
        return $this;
    }

    /**
     * Set status code to 204
     *
     * @return $this
     */
    public function noContent()
    {
        $this->setStatusCode(204);
        return $this;
    }

    /**
     * Set status code to 401
     *
     * @return $this
     */
    public function errorUnauthorized()
    {
        $this->setStatusCode(401);
        return $this;
    }

    /**
     * Set status code to 403
     *
     * @return $this
     */
    public function errorForbidden()
    {
        $this->setStatusCode(403);
        return $this;
    }

    /**
     * Set status code to 404
     *
     * @return $this
     */
    public function errorNotFound()
    {
        $this->setStatusCode(404);
        return $this;
    }

    /**
     * Add metadata to response
     *
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta)
    {

        $this->meta = $meta;
        $this->setMetaComputed();

        return $this;

    }

    /**
     * Add computed data to response
     *
     * @param array $computed
     * @return $this
     */
    public function setComputed(array $computed)
    {

        $this->computed = $computed;
        $this->setMetaComputed();

        return $this;
    }

    /**
     * Set Meta and Computed data
     */
    private function setMetaComputed()
    {

        $this->resource->setMeta(
            [
                'meta' => $this->meta,
                'computed' => $this->computed
            ]
        );
        $this->buildContent();

    }

    /**
     * Change response status code
     *
     * @param $code
     * @return $this
     */
    public function statusCode($code)
    {
        $this->setStatusCode($code);
        return $this;
    }

    /**
     * Set content with resource
     */
    protected function buildContent()
    {

        $this->setContent(
            $this->manager->createData($this->resource)->toArray()
        );

    }

}