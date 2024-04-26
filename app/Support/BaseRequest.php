<?php

namespace App\Support;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    public function getSortOptions() : array
    {
       return $this->only(["sortBy", "orderDirection", "perPage"]);
    }
}
