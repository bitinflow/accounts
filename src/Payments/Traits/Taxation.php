<?php

namespace Bitinflow\Payments\Traits;

use Bitinflow\Payments\Result;

trait Taxation
{
    /**
     * Get vat from user.
     */
    public function getTaxation(): Result
    {
        return $this->query('GET', 'taxation');
    }
}
