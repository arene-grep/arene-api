<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     *Determine if the event is authorized to make this request
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
            'name' => 'nullable',
            'date' => 'nullable|date',
            'trading_card_game_id' => 'exists:trading_card_games,id',
        ];
    }

}
