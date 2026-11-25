<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\Reservation;

class CreateReservationRequest extends FormRequest
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
            'name' => 'required',
            //'last_name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'birthday' => 'nullable|date',
            'store_id' => 'required',
            'people' => 'required|min:1',
            'reservation_date' => 'required|date',
            'reservation_hour' => 'required|between:1,24',
            'reservation_min' => 'required|in:0,30',
        ];
    }
}
