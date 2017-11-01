<?php

namespace App\Http\Requests;

use App\Core\Validation\ValidationScenario;

class TenantValidation extends ValidationScenario
{

    public function onDefault()
    {
        return [

        ];
    }

    public function onCreate()
    {
        return [
            'name' => 'required|unique'
        ];
    }

    public function onUpdate()
    {
        return [

        ];
    }
}
