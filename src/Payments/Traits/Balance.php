<?php

namespace Bitinflow\Payments\Traits;

use Bitinflow\Payments\Result;
use GuzzleHttp\Exception\GuzzleException;

trait Balance
{
    /**
     * Get balance from user.
     */
    public function getUser(): Result
    {
        return $this->query('GET', 'user');
    }

    /**
     * Deposit given amount from bank to account.
     */
    public function deposit(float $amount, string $description): Result
    {
        return $this->query('PUT', 'wallet/deposit', [], null, [
            'amount' => $amount,
            'description' => $description,
        ]);
    }

    /**
     * Charge given amount from account.
     *
     * @throws GuzzleException
     */
    public function charge(float $amount, string $description): bool
    {
        $result = $this->createOrder([
            'name' => $description,
            'description' => 'one-time payment',
            'amount' => 1,
            'price' => $amount,
        ]);

        return $this->checkoutOrder($result->data()->id);
    }
}
