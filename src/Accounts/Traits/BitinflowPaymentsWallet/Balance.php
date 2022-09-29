<?php

namespace Bitinflow\Accounts\Traits\BitinflowPaymentsWallet;

use App\Models\User;

class Balance
{
    public function __construct(protected User $user)
    {
        //
    }
  
    /**
     * Get balance from user.
     *
     * @return float|null
     */
    public function get(): ?float
    {
        return $this->user->getPaymentsUser()->data->balance;
    }

    /**
     * Deposit given amount from bank to account.
     *
     * @param float $amount
     * @param string $description
     * @return bool
     */
    public function deposit(float $amount, string $decription): bool
    {
        $this->user->asPaymentsUser('PUT', sprintf('wallet/deposit', [
            'amount' => $amount,
            'decription' => $decription,
        ]));
    }

    /**
     * Charge given amount from account.
     *
     * @param float $amount
     * @param string $decription
     * @return bool
     */
    public function charge(float $amount, string $decription): bool
    {
        $order = $this->user->orders()->create([
            'name' => $decription,
            'description' => 'one-time payment',
            'amount' => 1,
            'price' => $amount,
        ]);

        return $this->user->orders()->checkout($order->id);
    }
}
