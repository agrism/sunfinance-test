<?php

namespace App\Http\Requests;

use App\Enums\NotificationChannelEnum;
use App\Models\Client;
use App\Validators\CustomValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

/**
 *
 * @OA\Schema(
 *     schema="Notification",
 *     @OA\Property(type="string", property="clientId", example="1"),
 *     @OA\Property(type="string", property="channel", example="email"),
 *     @OA\Property(type="string", property="content", example="some content")
 * ),
 *
 * @OA\RequestBody(
 *  request="NotificationsStoreRequest",
 *  description="Client object that needs to be added to the store",
 *  @OA\JsonContent(
 *      type="array",
 *      @OA\Items(anyOf={
 *         @OA\Schema(ref="#/components/schemas/Notification"),
 *      })
 *  )
 * )
 */
class NotificationsStoreRequest extends FormRequest
{

    private const CLIENT_ID = 'clientId';
    private const CHANNEL = 'channel';
    private const CONTENT = 'content';
    private array $errors = [];
    private int $currentIndex = 0;
    private string $currenField = '';

    /**
     * @return true
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }

    public function validateResolved(): void
    {
        $this->prepareForValidation();

        if (!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $instance = app(CustomValidator::class, [
            'translator' => app(Translator::class),
            'data' => [],
            'rules' => $this->rules()]);

        $payload = $this->all();

        if(!is_array($payload)){
            $this->errors['payload'][] = 'payload should be array';
        }

        if(empty($payload)){
            $this->errors['payload'][] = 'payload array should contain at least one correct element';
        }

        foreach ($this->all() as $index => $notification) {

            if(!is_int($index)){
                $this->errors['collection'] = 'Post data should be array of objects';
                break;
            }
            $this->currentIndex = $index;

            $clientId = data_get($notification, self::CLIENT_ID);
            $channel = data_get($notification, self::CHANNEL);
            $content = data_get($notification, self::CONTENT);

            $this->setCurrentField(self::CLIENT_ID);

            if ($clientId === null) {
                $this->registerError('is required');
            } else {
                if (!is_numeric($clientId)) {
                    $this->registerError('must be numeric');
                } elseif (!Client::query()->where('id', $clientId)->exists()) {
                    $this->registerError('not found');
                }
            }

            $this->setCurrentField(self::CHANNEL);

            if (!$channel) {
                $this->registerError('is required');
            } elseif (!in_array($channel, $this->allowedChannels())) {
                $this->registerError(sprintf('value must be in: %s', implode(',', $this->allowedChannels())));
            }

            $this->setCurrentField(self::CONTENT);

            if ($content === null) {
                $this->registerError('is required');
            } elseif (!is_string($content)) {
                $this->registerError('must be string');
            } elseif ($channel === NotificationChannelEnum::SMS->value && strlen($content) > 140) {
                $this->registerError('max length is 140');
            }
        }

        if ($this->errors) {
            $instance->setErrors($this->errors);
            $this->failedValidation($instance);
        }

        $this->passedValidation();
    }

    private function allowedChannels(): array
    {
        return array_map(function (NotificationChannelEnum $channel) {
            return $channel->value;
        }, NotificationChannelEnum::cases());
    }

    private function setCurrentField(string $field): void
    {
        $this->currenField = $field;
    }

    private function registerError(string $message): void
    {
        $this->errors[$this->currenField][] = sprintf('%s.%s %s', $this->currentIndex, $this->currenField, $message);
    }
}
