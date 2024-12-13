<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'addressLine1' => ['required', 'string', 'max:100'],
            'addressLine2' => ['required', 'string', 'max:100'],
            'addressLine3' => ['required', 'string', 'max:100'],
            'mobile' => ['required', 'string', 'max:10', 'unique:contact_infos,mobile1','unique:contact_infos,mobile2', 'regex:/^[0-9]{10,15}$/'],

            //'school' => ['required', 'not_in:0'],
            'nic' => ['required', 'unique:users,nic', 'regex:/^([0-9]{9}[Vv]|[0-9]{12})$/'],
            'birthDay' => ['required', 'date', 'before:today'],
            'serviceDate' => ['required', 'date', 'after_or_equal:birthDay'],
        ];

        // Conditionally add 'zone' validation rule
        if ($this->has('zone')) {
            $rules['zone'] = ['required', 'not_in:0'];
        }
        //dd($this->has('school'));
        if ($this->has('school')) {
            $rules['school'] = ['required', 'not_in:0'];
        }

        if($this->has('subject')){
            $rules['subject'] = ['required', 'exists:subjects,id'];
        }

        if($this->has('medium')){
            $rules['medium'] = ['required', 'exists:appointment_media,id'];
        }

        
        //dd($this->input('school'));
        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The full name is required.',
            'name.string' => 'The full name must be a string.',
            'name.max' => 'The full name cannot exceed 100 characters.',

            'addressLine1.required' => 'Address Line 1 is required.',
            'addressLine1.string' => 'Address Line 1 must be a string.',
            'addressLine1.max' => 'Address Line 1 cannot exceed 100 characters.',

            'addressLine2.required' => 'Address Line 2 is required.',
            'addressLine2.string' => 'Address Line 2 must be a string.',
            'addressLine2.max' => 'Address Line 2 cannot exceed 100 characters.',

            'addressLine3.required' => 'Address Line 3 is required.',
            'addressLine3.string' => 'Address Line 3 must be a string.',
            'addressLine3.max' => 'Address Line 3 cannot exceed 100 characters.',

            'mobile.required' => 'The mobile number is required.',
            'mobile.string' => 'The mobile number must be a string.',
            'mobile.max' => 'The mobile number cannot exceed 10 characters.',
            'mobile.unique' => 'The mobile number has already been taken.',
            'mobile.regex' => 'The mobile number must be between 10 and 15 digits.',

            'subject.required' => 'The subject is required.',
            'subject.exists' => 'The selected subject is invalid.',

            'medium.required' => 'The appointment medium is required.',
            'medium.exists' => 'The selected medium is invalid.',

            'school.required' => 'The school is required.',
            'school.not_in' => 'Please select a valid school.',

            'zone.required' => 'The zone is required.',
            'zone.not_in' => 'Please select a valid zone.',

            'nic.required' => 'The NIC is required.',
            'nic.unique' => 'The NIC has already been taken.',
            'nic.regex' => 'The NIC format is invalid. It must be either 9 digits followed by V/v or 12 digits.',

            'birthDay.required' => 'The birth date is required.',
            'birthDay.date' => 'The birth date must be a valid date.',
            'birthDay.before' => 'The birth date must be before today.',

            'serviceDate.required' => 'The service appointed date is required.',
            'serviceDate.date' => 'The service appointed date must be a valid date.',
            'serviceDate.after_or_equal' => 'The service appointed date must be after or equal to the birth date.',
        ];
    }

}
