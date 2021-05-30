<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Wallet;

class PayerUserType implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    private $message_error;

    public function passes($attribute, $value)
    {
        $wallet = Wallet::find($value);

        if ($wallet) {
            if ($wallet->user) {
                if ($wallet->user->corporate) {
                    $this->message_error = 'User corporate not enable for this transaction.';
                    return false;
                }
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
