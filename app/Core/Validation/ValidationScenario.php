<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 26.10.17
 * Time: 19:19
 */

namespace App\Core\Validation;


use Illuminate\Foundation\Http\FormRequest;

class ValidationScenario extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * we set it to true as default cause we use policies
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
        $method = strtolower($this->getMethod());

        if ($method === 'post') {
            return $this->onCreate();
        }

        if ($method === 'put') {
            return $this->onUpdate();
        }

        return $this->onDefault();
    }

    public function onDefault()
    {
        return [

        ];
    }

    public function onCreate()
    {
        return [

        ];
    }

    public function onUpdate()
    {
        return [

        ];
    }
}