<?php

namespace App\Http\Requests\Reporter;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class LoginRequest extends FormRequest
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
            'phone' => 'required',
            'password' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'phone.required' => 'this is field required',
            'password.required' => 'this is field required',

        ];
    }
    /**
     * Get the needed authorization credentials from the request.
     *
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getCredentials()
    {
        // The form field for providing phone or password
        // have name of "phone", however, in order to support
        // logging users in with both (phone and email)
        // we have to check if user has entered one or another
        $phone = $this->get('phone');

        if ($this->isEmail($phone)) {
            return [
                'email' => $phone,
                'password' => $this->get('password')
            ];
        }

        return $this->only('phone', 'password');
    }

    /**
     * Validate if provided parameter is valid email.
     *
     * @param $param
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function isEmail($param)
    {
        $factory = $this->container->make(ValidationFactory::class);

        return ! $factory->make(
            ['phone' => $param],
            ['phone' => 'email']
        )->fails();
    }
}
