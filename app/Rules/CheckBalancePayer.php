<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Wallet;

class CheckBalancePayer implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    private $message_error;
    private $data;

    public function __construct($data)
    {
        $this->data = (object) $data;
    }

    public function passes($attribute, $value)
    {
        $wallet = Wallet::find($this->data->payer_wallet_id);

        if ($wallet) {
            if ($wallet->balance < $value) {
                $this->message_error = 'Payer without balance for this transaction.';
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans($this->message_error);
    }
}
