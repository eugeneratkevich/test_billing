<?php

namespace App\Http\Requests;

class StoreUsersTransfer extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from' => 'required|numeric|min:1|exists:users,id',
            'to' => 'required|numeric|min:1|not_same:from',
            'amount' => 'required|numeric|min:1',
        ];
    }
}
