<?php

namespace App\Http\Requests;

class StoreUserAmount extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user' => 'required|numeric|min:1|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ];
    }
}
