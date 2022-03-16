<?php
namespace Domain\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBroadcastServiceCredentialsRequest extends FormRequest
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
            'driver'  => 'required|string',
            'id'      => 'sometimes|nullable|string',
            'key'     => 'sometimes|nullable|string',
            'secret'  => 'sometimes|nullable|string',
            'cluster' => 'sometimes|nullable|string',
            'port'    => 'sometimes|nullable|string',
            'host'    => 'sometimes|nullable|string',
        ];
    }
}
