<?php

namespace App\Order\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int         $order_id
 * @property int         $code
 * @property string|null $message
 */
class NotifyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => ['integer', 'exists:orders,id'],
            'code'     => ['integer'],
            'message'  => ['nullable', 'string'],
        ];
    }
}
