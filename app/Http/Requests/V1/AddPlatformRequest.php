<?php

declare(strict_types=1);

namespace App\Http\Requests\V1;

use App\Enums\BBPlatform;
use App\Queries\UserPlatform;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

final class AddPlatformRequest extends FormRequest
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
        return [
            'platform' => ['required', 'string'],
            'username' => ['required', 'string'],
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validatedData = parent::validated($key, $default);
        $this->validate(rules: ['platform' => [Rule::in(BBPlatform::getValues())]]);

        $userNames = app(UserPlatform::class)->getUserNamesByPlatform(
            platform: BBPlatform::tryFrom($this->get('platform'))
        );

        $this->validate(rules: ['username' => [Rule::notIn($userNames)]]);

        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['verified'] = false;

        return $validatedData;
    }
}
