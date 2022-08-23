<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class AddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->has('status'))
            return ['status' => 'required', 'reason' => 'required'];
        else
            return [
                'codeTransaction' => 'required',
                'dateTransaction'   => 'required',
                'codeCustomer'      => 'required',
                'TotalDiscount'     => 'required|numeric|min:0',
                'CodeProduct.*'     => 'required',
                'QtyProduct.*'      => 'required',
                'DiscountProduct.*' => 'required',
                'shippingCost'     => 'required|numeric',
                'GrandTotal'        => 'required',
                'SubTotal'          => 'required'
            ];
    }
}
