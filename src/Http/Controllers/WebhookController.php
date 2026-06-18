<?php

namespace The42dx\Whatsapp\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Entities\{ChangesEntity, EventEntity};
use The42dx\Whatsapp\Enums\ApiEvent;
use The42dx\Whatsapp\Http\Controllers\Traits\Messages\HandleWhatsappMessage;
use The42dx\Whatsapp\Http\Requests\{ApiEventRequest, WebhookCheckRequest};

/**
 * WebhookController
 *
 * Controller responsible for handling Whatsapp webhook actions and data.
 */
class WebhookController extends Controller {
    use HandleWhatsappMessage;

    /**
     * ERROR_INVALID_VERIFY
     *
     * Error message for invalid verify token
     *
     * @var string
     */
    public const ERROR_INVALID_VERIFY = 'Invalid verify token';

    /**
     * check
     *
     * Endpoint handler for the Whatsapp Business API webhook subscription check.
     *
     * @param  \The42dx\Whatsapp\Http\Requests\WebhookCheckRequest  $request  The request object
     * @return \Illuminate\Http\Response The response object containing the challenge sent
     */
    public function check(WebhookCheckRequest $request): Response {
        Log::info('Whatsapp verify_token match');

        return response($request->hub_challenge);
    }

    /**
     * handle
     *
     * Endpoint handler for processing incoming Whatsapp Business API webhook events.
     * It unwraps the relevant data from the data arrays and routes them to the appropriate handlers.
     *
     * @param  \The42dx\Whatsapp\Http\Requests\ApiEventRequest  $request  The request object containing the event data
     */
    public function handle(ApiEventRequest $request): void {
        Log::debug('Whatsapp event received: ' . json_encode($request->all()));

        $event = new EventEntity($request->all());

        $event->entries->each(function($entry): void {
            $entry->changes->each(function($change): void {
                $this->hookRouter($change);
            });
        });
    }

    /**
     * hookRouter
     *
     * Routes the incoming change entity to the appropriate handler based on the field type.
     *
     * @param  \The42dx\Whatsapp\Entities\ChangesEntity  $change  The change entity to be processed
     */
    private function hookRouter(ChangesEntity $change): void {
        switch ($change->field) {
            case ApiEvent::MSGS:
                $this->handleMessages($change->value);
                break;
            case ApiEvent::ACC_ALERTS:
            case ApiEvent::ACC_REVIEW_UPDATE:
            case ApiEvent::ACC_UPDT:
            case ApiEvent::BUSINESS_CAPABILITY_UPDT:
            case ApiEvent::BUSINESS_STATUS_UPDT:
            case ApiEvent::CAMPAIGN_STATUS_UPDT:
            case ApiEvent::FLOWS:
            case ApiEvent::MSG_ECHOES:
            case ApiEvent::MSG_HANDOVERS:
            case ApiEvent::MSG_TPLT_QUALITY_UPDT:
            case ApiEvent::MSG_TPLT_STATUS_UPDT:
            case ApiEvent::PARTNER_SOLUTIONS:
            case ApiEvent::PHONE_NUM_NAME_UPDT:
            case ApiEvent::PHONE_NUM_QUALITY_UPDT:
            case ApiEvent::SECURITY:
            case ApiEvent::TEMPLATE_CAT_UPDT:
            default:
                $this->handleDefault($change);
                break;
        }
    }

    /**
     * handleDefault
     *
     * Default handler for unsupported or unrecognized API events.
     *
     * @param  \The42dx\Whatsapp\Entities\ChangesEntity  $change  The change entity to be processed
     */
    protected function handleDefault(ChangesEntity $change): void {
        $changeData = $change->toArray();

        Log::warning("Unsupported API event: {$changeData['field']}");
        Log::debug('Unsupported API event data', ['data' => $changeData]);
    }
}
