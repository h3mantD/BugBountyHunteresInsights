<?php

declare(strict_types=1);

namespace App\Http\Requests\V1;

use App\Enums\BBPlatform;
use App\Queries\UserPlatform;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\UnauthorizedException;

final class DeletePlatformRequest extends FormRequest
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
        if (
            ! app(UserPlatform::class)
                ->isIfPlatformIsAttachedToUser(
                    platform: BBPlatform::tryFrom($this->get('platform')),
                    username: $this->get('username')
                )
        ) {
            throw new UnauthorizedException('Unauthorized to delete this platform!');
        }

        return $validatedData;
    }
}
