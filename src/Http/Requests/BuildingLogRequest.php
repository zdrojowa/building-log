<?php

namespace Selene\Modules\BuildingLogModule\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class BuildingLogRequest extends FormRequest
{

    public function rules() {
        return [
            'month' => [
                'required',
            ],
            'year' => [
                'required',
                Rule::unique('mongodb.building_logs')->where('month', $this->input('month'))->where('year', $this->input('year'))
            ]
        ];
    }

    public function messages()
    {
        return [
            'year.unique' => 'Taka zakładka już istnieje',
            '*.required' => 'To pole jest wymagane'
        ];
    }
}
