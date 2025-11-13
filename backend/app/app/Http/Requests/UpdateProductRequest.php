<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        $productId = $this->route('product');
        $user = $this->user();

        $rules = [
            'name' => ['sometimes', 'required', 'string', 'min:10'],
            'status' => ['sometimes', 'nullable', 'string', 'in:available,unavailable'],
            'data' => ['sometimes', 'nullable', 'array'],
        ];

        if ($user && $user->role === 'admin') {
            $rules['article'] = [
                'sometimes',
                'required',
                'string',
                'regex:/^[a-zA-Z0-9]+$/',
                Rule::unique('products', 'article')->ignore($productId),
            ];
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();

            if ($this->has('article') && $user && $user->role !== 'admin') {
                $validator->errors()->add('article', 'У вас нет прав для изменения артикула. Только администратор может редактировать артикул.');
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'article.required' => 'Артикул обязателен для заполнения.',
            'article.regex' => 'Артикул должен содержать только латинские символы и цифры.',
            'article.unique' => 'Продукт с таким артикулом уже существует.',
            'name.required' => 'Название обязательно для заполнения.',
            'name.min' => 'Название должно содержать минимум 10 символов.',
            'status.in' => 'Статус должен быть "available" или "unavailable".',
            'data.array' => 'Поле data должно быть массивом.',
        ];
    }
}
