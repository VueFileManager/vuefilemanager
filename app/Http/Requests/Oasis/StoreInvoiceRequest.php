<?php
namespace App\Http\Requests\Oasis;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
        return [
            'invoice_type' => 'required|string',
            'invoice_number' => 'required|string',
            'variable_number' => 'required|string',
            'client' => 'required',
            'items' => 'required|string',
            'discount_type' => 'sometimes|string',
            'discount_rate' => 'sometimes|integer',
            'delivery_at' => 'required|date',
        ];
    }
}
