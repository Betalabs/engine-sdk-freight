<?php

namespace Betalabs\Engine\Contracts;


interface ZipCodeRangeCalculator
{
    public function setZipCode(string $zipCode);
    public function setItemsIds(array $itemsIds);
    public function setQuantities($quantities);
    public function calculate();
}