# Whatsapp Laravel SDK

This Laravel package's goal is to abstract and simplify integration with Facebook's Whatsapp Business API through services, models, traits, and message builders, while documenting how to work with it correctly.

Check our package on [Packagist](https://packagist.org/packages/42dx/whatsapp-laravel-sdk).

## Project Meta Data

[![All Contributors](https://img.shields.io/github/all-contributors/42dx/whatsapp-laravel-sdk?color=ee8449&flat&label=Contributors)](https://github.com/42dx/whatsapp-laravel-sdk/blob/main/README.md#contributors)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=42dx_whatsapp-laravel-sdk&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=42dx_whatsapp-laravel-sdk)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=42dx_whatsapp-laravel-sdk&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=42dx_whatsapp-laravel-sdk)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=42dx_whatsapp-laravel-sdk&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=42dx_whatsapp-laravel-sdk)
[![Vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=42dx_whatsapp-laravel-sdk&metric=vulnerabilities)](https://sonarcloud.io/summary/new_code?id=42dx_whatsapp-laravel-sdk)

## Project Status (`v1.0`, `main` branch)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=42dx_whatsapp-laravel-sdk&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=42dx_whatsapp-laravel-sdk)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=42dx_whatsapp-laravel-sdk&metric=bugs)](https://sonarcloud.io/summary/new_code?id=42dx_whatsapp-laravel-sdk)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=42dx_whatsapp-laravel-sdk&metric=code_smells)](https://sonarcloud.io/summary/new_code?id=42dx_whatsapp-laravel-sdk)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=42dx_whatsapp-laravel-sdk&metric=coverage)](https://sonarcloud.io/summary/new_code?id=42dx_whatsapp-laravel-sdk)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=42dx_whatsapp-laravel-sdk&metric=sqale_index)](https://sonarcloud.io/summary/new_code?id=42dx_whatsapp-laravel-sdk)

## Table of Contents

- [Composer Scripts](#composer-scripts)
- [Installation](#installation)
  - [Requirements](#requirements)
- [Configure Whatsapp Webhook](#configure-whatsapp-webhook)
- [Using the Package](#using-the-package)
  - [Migrations](#migrations)
    - [`whatsapp_messages` Table](#whatsapp_messages-table)
    - [Messageable Migration](#messageable-migration)
  - [General Concepts](#general-concepts)
    - [Sending Messages](#sending-messages)
    - [Message Templates](#message-templates)
    - [Available Templates](#available-templates)
    - [Message Support](#message-support)
    - [Whatsapp API Entities](#whatsapp-api-entities)
  - [Meta Webhook Validation](#meta-webhook-validation)
  - [Receiving Messages](#receiving-messages)
- [Run and Test](#run-and-test)
- [Tooling](#tooling)
  - [Comitzen](#comitizen)
  - [Pinggy](#pinggy)
    - [How to use Pinggy](#how-to-use-pinggy)
- [Contributors](#contributors)
- [Changelog](#changelog)
- [Roadmap](#roadmap)

## Composer Scripts

We tried to automate as much boring tasks and CLI commands as we could on short `composer` scripts. Below you will find a general description of what each one does:

- `composer commit`: Runs the [`comitzen` binary](https://github.com/lintingzhen/commitizen-go) which opens an interactive CLI to keep commits in a standard that our pipeline can use to generate the package changelog automatically.
- `composer coverage`: Run the tests and generates code coverage. If you are contributing, please make sure you do not lower the current coverage. ;)
- `composer start`: Starts the sample application through Laravel Sail with the package already loaded.
- `composer connect`:  Connects to Pinggy service and exposes local port `80` to the web. It assumes the sample app is running through Sail.
- `composer setup`: Prepares the sample application: installs dependencies, creates `.env` when missing, generates the app key, starts Sail, waits for MySQL, and runs migrations.
- `composer stop`: Stops the sample application containers.
- `composer test`: Run the tests (do not generate coverage report)
- `composer lint`: Checks PHP code style with Laravel Pint.

## Installation

### Requirements

- A Laravel application with Composer package auto-discovery enabled, or manual service provider registration.
- A configured WhatsApp Business Account, phone number ID, business account ID, and API token from Meta.
- The examples use the Laravel 11/12 `bootstrap/providers.php` provider registration style.

Install the package through Composer:

```bash
composer require 42dx/whatsapp-laravel-sdk
```

The package supports Laravel package auto-discovery and registers both service providers through `composer.json`. If auto-discovery is disabled in your application, register the providers manually in `bootstrap/providers.php`:

```php
return [
    // [...]
    The42dx\Whatsapp\RouteServiceProvider::class,
    The42dx\Whatsapp\WhatsappServiceProvider::class,
];
```

Publish the configuration file before running migrations so you can adjust table names, messageable columns, and the user model:

```bash
php artisan vendor:publish --tag=whatsapp-business-api-config
```

<p align="right"><em><a href="#table-of-contents">back to top</a></em></p>

## Configure Whatsapp Webhook

The package automatically registers two routes to handle Meta's webhook interactions:

| Method | URI | Controller Method | Purpose |
| ------ | --- | ----------------- | ------- |
| GET | `{webhook_route}` | `WebhookController@check` | Webhook verification handshake |
| POST | `{webhook_route}` | `WebhookController@handle` | Receive webhook events |

The default URI is `webhook/whatsapp`. You can customize it by setting `WHATSAPP_WEBHOOK_ROUTE` in your `.env` file:

```env
WHATSAPP_WEBHOOK_ROUTE=custom/webhook/path
```

### Meta Dashboard Setup

1. Go to your app on the [Meta Developer Dashboard](https://developers.facebook.com/).
2. Navigate to **WhatsApp** → **Configuration** → **Webhook**.
3. Click **Edit** and set the **Callback URL** to `https://your-domain.com/{webhook_route}`.
4. Set the **Verify Token** to a string of your choice — this must match the `WHATSAPP_WEBHOOK_VERIFY` value in your `.env` file.
5. Subscribe to the webhook fields you need (at minimum, **messages**).

### Environment Variables

Add the following to your `.env` file:

```env
WHATSAPP_API_VERSION=v20.0
WHATSAPP_BUSINESS_ID=
WHATSAPP_BUSINESS_PHONE_ID=
WHATSAPP_BUSINESS_PHONE_NUMBER=
WHATSAPP_SERVER_URL=https://graph.facebook.com
WHATSAPP_TOKEN=
WHATSAPP_WEBHOOK_VERIFY=
WHATSAPP_WEBHOOK_ROUTE=webhook/whatsapp
WHATSAPP_DEFAULT_TEMPLATE_LANGUAGE=en_US
```

| Variable | Required | Default | Description |
| -------- | -------- | ------- | ----------- |
| `WHATSAPP_API_VERSION` | No | `v20.0` | Meta Graph API version to use |
| `WHATSAPP_BUSINESS_ID` | Yes | — | Your WhatsApp Business Account ID |
| `WHATSAPP_BUSINESS_PHONE_ID` | Yes | — | The phone number ID from your WhatsApp Business dashboard |
| `WHATSAPP_BUSINESS_PHONE_NUMBER` | Yes | — | The phone number associated with your Business account |
| `WHATSAPP_SERVER_URL` | No | `https://graph.facebook.com` | Base URL for the WhatsApp API |
| `WHATSAPP_TOKEN` | Yes | — | Permanent or temporary access token for the API |
| `WHATSAPP_WEBHOOK_VERIFY` | Yes | — | Verify token for webhook validation (must match Meta dashboard) |
| `WHATSAPP_WEBHOOK_ROUTE` | No | `webhook/whatsapp` | URI path where Meta will send webhook events |
| `WHATSAPP_DEFAULT_TEMPLATE_LANGUAGE` | No | `en_US` | Default language code for message templates |

> **Important:** The `WHATSAPP_WEBHOOK_VERIFY` value must exactly match the verify token you entered on the Meta dashboard. The package uses it to validate the webhook verification request from Meta.

<p align="right"><em><a href="#table-of-contents">back to top</a></em></p>

## Using the Package

After installing, adjust the package configuration according to your Meta application, then run the migrations to create the tables and columns the package needs.

### Migrations

The package will create a `whatsapp_messages` table to store the messages (both inbound and outbound) handled by the package. It will also create a unique messageable phone column on your messageable entity (default: `phone` on the `users` table).

If you need to customize this, adjust the `database.*` keys in your `/config/whatsapp.php` file **before running the migrations**.

If your application owns its migrations manually, set `database.skip_migrations` to `true` in `config/whatsapp.php` and create equivalent application migrations.

To keep everything nice and tidy in your project, it is recommended (but not necessary) to publish the default migrations. You can do so by running the following command:

```bash
php artisan vendor:publish --tag=whatsapp-business-api-migrations
```

Then run your application migrations:

```bash
php artisan migrate
```

#### `whatsapp_messages` Table

This table stores persisted WhatsApp message records — both inbound messages received from contacts and outbound messages sent by your application. Reactions are stored as payload metadata on the referenced message instead of being stored as independent message rows. Below is the schema:

| Column | Type | Nullable | Default | Description |
| ------ | ---- | -------- | ------- | ----------- |
| `id` | bigint (auto-increment) | No | — | Primary key |
| `whatsapp_message_id` | varchar | No | — | WhatsApp message ID (unique) |
| `contact_phone_number` | varchar | No | — | Phone number of the contact |
| `{messageable_id_column}` | foreignId | Yes | — | FK to your messageable table (default: `user_id` → `users.id`), with cascade delete |
| `way` | enum (`inbound`, `outbound`) | No | — | Message direction |
| `status` | enum (`pending`, `sent`, `delivered`, `read`, `failed`, `deleted`, `warning`) | No | `pending` | Delivery status |
| `type` | enum (`audio`, `button`, `contacts`, `document`, `image`, `interactive`, `location`, `reaction`, `sticker`, `template`, `text`, `unsupported`, `video`) | No | — | Message type |
| `text` | text | Yes | — | Text body (for text/button messages) |
| `payload` | json | Yes | — | Full message data including context, reactions, and template components |
| `whatsapp_deleted_at` | timestamp | Yes | — | When Meta reported the message as deleted |
| `created_at` | timestamp | Yes | — | Laravel managed |
| `updated_at` | timestamp | Yes | — | Laravel managed |
| `deleted_at` | timestamp | Yes | — | Laravel soft delete timestamp |
| `delivered_at` | timestamp | Yes | — | When Meta confirmed delivery |
| `read_at` | timestamp | Yes | — | When the contact read the message |
| `sent_at` | timestamp | Yes | — | When Meta confirmed the message was sent |

**Indexes:** `whatsapp_message_id` (unique), `contact_phone_number`, `way`, `status`, `type`.

The table name defaults to `whatsapp_messages` but can be changed via `config('whatsapp.database.table_name')`. The `payload` column is automatically cast to a PHP array of payload entries on the `WhatsappMessage` model:

```php
$message = WhatsappMessage::first();
$firstPayloadItem = $message->payload[0] ?? null;
```

You can also filter the payload by type using the model's helper methods:

```php
// Get only context payload items for reply messages
$replyContext = $message->getPayloadType(ContextType::REPLY);

// Get all payload items except reaction data
$withoutReactions = $message->getPayloadWithoutType(MessageType::REACTION);
```

#### Messageable Migration

The package also adds the configured messageable phone column to your messageable table (default: `phone` on `users`) so you can associate WhatsApp messages with your application's users. The migration adds:

| Column | Type | Nullable | Unique | Description |
| ------ | ---- | -------- | ------ | ----------- |
| `{messageable_phone_column}` | varchar | Yes | Yes | Contact's WhatsApp phone number (default: `phone`) |
| `{messageable_phone_column}_verified_at` | timestamp | Yes | No | Phone verification timestamp (default: `phone_verified_at`) |

The phone column name is configurable via `config('whatsapp.database.messageable_phone_column')` before running the migrations. The verification timestamp column is derived from it by appending `_verified_at`:

```php
// config/whatsapp.php
'database' => [
    'messageable_phone_column' => 'phone',       // column name for the phone number
    'skip_migrations'          => false,          // set true to disable package-loaded migrations
    'table_name'               => 'whatsapp_messages',
    'users_table'              => 'users',        // table to alter
    'users_table_pk'           => 'id',            // primary key of the users table
    'messageable_id_column'    => 'user_id',       // FK column on whatsapp_messages
    'user_model'               => App\Models\User::class, // model used for lookups
],
```

> **Note:** If your application already has a `phone` column on the `users` table, change `messageable_phone_column` to a different name **before** running the migrations to avoid conflicts.

> **Note:** If you publish and customize the messageable migration, keep the `down()` method aligned with both columns. Only `{messageable_phone_column}` has a unique index by default.

### General Concepts

The package follows the same data structure as the [WhatsApp Business API](https://developers.facebook.com/docs/whatsapp/cloud-api). Understanding a few key concepts will help you work with it effectively:

#### Message Direction

Every message stored in the `whatsapp_messages` table has a `way` column that indicates its direction:

- **Inbound** — messages received from a WhatsApp contact
- **Outbound** — messages sent by your application

#### Message Lifecycle

When Meta processes a message, it sends status updates via the webhook. The schema supports all statuses below, but the webhook handler currently updates timestamps only for `sent`, `delivered`, `read`, and `deleted`.
Unhandled statuses, such as `failed` and `warning`, are valid column values but are logged instead of timestamped by the current handler.

| Status | Timestamp Column | Meaning |
| ------ | ---------------- | ------- |
| `pending` | — | Message created but not yet confirmed by Meta |
| `sent` | `sent_at` | Meta confirmed the message was sent |
| `delivered` | `delivered_at` | Meta confirmed delivery to the contact's device |
| `read` | `read_at` | The contact read the message |
| `deleted` | `whatsapp_deleted_at` | Meta reported the message as deleted |
| `failed` | Not handled | Meta could not deliver the message |
| `warning` | Not handled | A non-critical issue was flagged |

#### The `payload` Column

The `payload` JSON column stores structured data that varies by message type — context (replies, forwards), reaction data, template components, and button payloads. This allows the package to preserve the full message structure without requiring separate tables for each type.

#### Sending Messages

To send messages, add the `CanSendWhatsappMsg` trait to any Eloquent model (typically your `User` model). The model must expose the configured `messageable_phone_column` attribute, which defaults to `phone`, matching the contact's WhatsApp number:

```php
use The42dx\Whatsapp\Enums\{MessageComponent, MessageType};
use The42dx\Whatsapp\Models\Traits\CanSendWhatsappMsg;

class User extends Authenticatable
{
    use CanSendWhatsappMsg;
}
```

Then you can send messages through the model:

```php
$phoneColumn = config('whatsapp.database.messageable_phone_column', 'phone');
$user = User::where($phoneColumn, '+1234567890')->first();

// Send a text message
$user->sendWhatsappMsg(MessageType::TEXT, 'Hello from the SDK!');

// Send a template message
$user->sendWhatsappMsg(MessageType::TEMPLATE, [
    'name' => 'hello_world',
    'lang' => 'en_US',
]);

// React to a message
$user->sendWhatsappMsg(MessageType::REACTION, '👍', $originalMessage);
```

For template messages, `name` is required and must match the template name approved in Meta. `lang` is optional and falls back to `WHATSAPP_DEFAULT_TEMPLATE_LANGUAGE`.

You can also use the `WhatsappService` directly via dependency injection:

```php
use The42dx\Whatsapp\Factories\WhatsappApiMessage;
use The42dx\Whatsapp\Services\WhatsappService;

public function __construct(
    protected WhatsappService $whatsapp,
) {}

public function send(): void
{
    $message = WhatsappApiMessage::compose('+1234567890')
        ->with(text: 'Hello from the SDK!');

    $response = $this->whatsapp->send($message);
}
```

Or resolve it from the container:

```php
$whatsapp = app(WhatsappService::class);

$message = WhatsappApiMessage::make('+1234567890')
    ->withText('Hello from the SDK!');

$response = $whatsapp->send($message, $user);
```

#### Message Templates

Template components are passed in the same structure used by `WhatsappApiMessage::withComponent()`. The `CanSendWhatsappMsg` trait expects each component to include a `type` and a `parameters` array. Use at most one component for each `MessageComponent` type, matching WhatsApp API expectations.

```php
$user->sendWhatsappMsg(MessageType::TEMPLATE, [
    'name' => 'order_update',
    'lang' => 'en_US',
    'components' => [
        [
            'type' => MessageComponent::BODY,
            'parameters' => [
                ['name' => 'customer_name', 'text' => 'Rafael'],
                ['name' => 'order_code', 'text' => 'DX-123'],
            ],
        ],
        [
            'type' => MessageComponent::BUTTON,
            'subType' => MessageComponent::COPY_CODE,
            'index' => 0,
            'parameters' => [
                [
                    'type' => MessageComponent::COUPON_CODE,
                    'couponCode' => 'SAVE10',
                ],
            ],
        ],
    ],
]);
```

When using the builder directly, call `usingTemplate()` and add components with `withComponent()`:

```php
$message = WhatsappApiMessage::compose('+1234567890')
    ->usingTemplate('order_update', 'en_US')
    ->withComponent(MessageComponent::BODY, [
        ['name' => 'customer_name', 'text' => 'Rafael'],
        ['name' => 'order_code', 'text' => 'DX-123'],
    ]);

$response = app(WhatsappService::class)->send($message, $user);
```

#### Available Templates

Use `WhatsappService::getMessageTemplates()` to retrieve templates available for the configured WhatsApp Business Account:

```php
use The42dx\Whatsapp\Services\WhatsappService;

$templates = app(WhatsappService::class)->getMessageTemplates();
```

The method returns the decoded API response as an array. If Meta returns an error, the package logs it and returns an empty array.

#### Message Support

Outbound support through `CanSendWhatsappMsg`:

| `MessageType` | Supported | Behavior |
| ------------- | --------- | -------- |
| `TEXT` | Yes | Sends text and stores an outbound `WhatsappMessage` |
| `TEMPLATE` | Yes | Sends template data and stores the template payload |
| `REACTION` | Yes | Adds or removes reaction payload on an existing stored message |
| `AUDIO`, `BUTTON`, `CONTACTS`, `DOCUMENT`, `IMAGE`, `INTERACTIVE`, `LOCATION`, `STICKER`, `VIDEO`, `UNSUPPORTED` | No | Logs a warning and does not call the API |

Inbound support through the webhook:

| Inbound data | Supported | Behavior |
| ------------ | --------- | -------- |
| Text messages | Yes | Stores text and base message data |
| Button replies | Yes | Stores button text and payload |
| Reactions | Yes | Adds or removes reaction payload on the referenced stored message without creating a separate row |
| Context and status updates | Yes | Stores reply/forward metadata and delivery timestamps |
| Audio, contacts, document, image, interactive, location, sticker, video | Partial | Stores the generic message row, logs unsupported type, and does not persist type-specific payload fields |

#### Whatsapp API Entities

When Meta sends a webhook event, the payload follows a nested structure. The package maps this structure to a hierarchy of entity objects, each representing a level of the API response:

```txt
EventEntity
 └── EntryEntity[]
      └── ChangesEntity[]
           ├── ContactsEntity[]      (when field = MESSAGES)
           ├── MessageEntity[]       (when field = MESSAGES)
           └── StatusEntity[]        (when field = MESSAGES)
```

**Top-level entities:**

| Entity | Key Properties | Description |
| ------ | -------------- | ----------- |
| `EventEntity` | `$object` (ObjectType), `$entries` (Collection) | Root of every webhook event |
| `EntryEntity` | `$id` (string), `$changes` (Collection) | Represents a WhatsApp Business Account entry |
| `ChangesEntity` | `$field` (ApiEvent), `$value` (Entity) | A single change — the `$field` determines the value type |

**Message-related entities (under `ChangesEntity` when `$field` is `MSGS`):**

| Entity | Key Properties | Description |
| ------ | -------------- | ----------- |
| `MessagesEntity` | `$contacts`, `$messages`, `$statuses`, `$waId`, `$phone` | Container for all message data in a single change |
| `ContactsEntity` | `$name`, `$waId` | Contact profile info from an inbound message |
| `MessageEntity` | `$id`, `$from`, `$type`, `$text`, `$timestamp`, `$context`, + type-specific props | A single inbound or outbound message |
| `StatusEntity` | `$id`, `$recipientNumber`, `$status`, `$timestamp` | A delivery status update |

**Message sub-entities (accessed via `MessageEntity` properties):**

| Entity | Accessed Via | Key Properties |
| ------ | ------------ | -------------- |
| `ContextEntity` | `$message->context` | `$id`, `$from`, `$type` (REPLY, FWD, F_FWD) |
| `ReactionEntity` | `$message->reaction` | `$emoji`, `$messageId` |
| `ButtonEntity` | `$message->button` | `$text`, `$payload` |
| `LocationEntity` | `$message->location` | `$latitude`, `$longitude`, `$address`, `$name` |
| `ContactEntity` | `$message->contacts` | `$name`, `$phones`, `$emails`, `$addresses`, `$org` |
| `ImageEntity` | `$message->image` | `$id`, `$caption`, `$mimeType` |
| `VideoEntity` | `$message->video` | `$id`, `$caption`, `$mimeType` |
| `DocumentEntity` | `$message->document` | `$id`, `$caption`, `$filename`, `$mimeType` |
| `AudioEntity` | `$message->audio` | `$id`, `$mimeType` |
| `StickerEntity` | `$message->sticker` | `$id`, `$type` (STATIC, ANIMATED) |

All entities implement the `Entity` contract, providing `toArray()` and `toJson()` methods for serialization, and dynamic property access via `__get()`.

<p align="right"><em><a href="#table-of-contents">back to top</a></em></p>

### Meta Webhook Validation

When you configure a webhook in the Meta Dashboard, Meta sends a `GET` request to verify your endpoint. The package handles this automatically via the `WebhookController@check` route.

**How it works:**

1. Meta sends a `GET` request with `hub_mode`, `hub_verify_token`, and `hub_challenge` query parameters
2. The `WebhookCheckRequest` form request validates all three:
   - `hub_mode` — must be `subscribe` (enforced by the `HubMode` rule)
   - `hub_verify_token` — must match your `config('whatsapp.webhook_verify')` value (enforced by the `VerifyToken` rule)
   - `hub_challenge` — must be a present string
3. If validation passes, the controller responds with the `hub_challenge` value — this confirms to Meta that your endpoint is valid

**Setup in Meta Dashboard:**

1. Set `WHATSAPP_WEBHOOK_VERIFY` in your `.env` to a secret string of your choice
2. In the Meta Dashboard → WhatsApp → Configuration → Webhook, enter your callback URL and the same verify token
3. Meta will send the verification request — if the token matches, the webhook is activated

If verification fails, check that the `WHATSAPP_WEBHOOK_VERIFY` value in `.env` exactly matches what you entered in the Meta Dashboard.

<p align="right"><em><a href="#table-of-contents">back to top</a></em></p>

### Receiving Messages

When Meta forwards a webhook event, the `WebhookController@handle` endpoint processes it automatically. The incoming `POST` request is validated by `ApiEventRequest` (requires `object` to be a valid ObjectType and `entry` to be a non-empty array), then the payload is parsed into the entity hierarchy.

**Event routing:**

The controller's `hookRouter` dispatches each `ChangesEntity` based on its `$field`:

| `ApiEvent` field | Handler | Behavior |
| ---------------- | ------- | -------- |
| `MSGS` | `handleMessages()` | Processes inbound messages and status updates |
| All other fields | `handleDefault()` | Logs a warning and ignores the event |

**Message processing (`MSGS` field):**

1. **Inbound messages** — Each `MessageEntity` is processed by `handleMessage()`:
   - Creates or updates a `WhatsappMessage` model with `way = INBOUND`, the contact's phone number, type, and WhatsApp message ID
   - Attempts to associate the message with a user by matching `from` against the configurable `messageable_phone_column`
   - Dispatches to a type-specific handler:

   | `MessageType` | Handler | Behavior |
   | ------------- | ------- | -------- |
   | `TEXT` | `handleText()` | Stores the text body |
   | `REACTION` | `handleReaction()` | Adds or removes reaction payload on the referenced stored message without creating a separate row |
   | `BUTTON` | `handleButton()` | Stores the button text/payload |
   | `AUDIO`, `CONTACTS`, `DOCUMENT`, `IMAGE`, `INTERACTIVE`, `LOCATION`, `STICKER`, `VIDEO` | — | Stores generic message data and logs an unsupported-type warning |

   - Processes context (reply/forward) via `handleContext()`, storing it in the `payload` column
   - Persists via `updateOrCreate` using `whatsapp_message_id` + `contact_phone_number` as the unique key

2. **Status updates** — Each `StatusEntity` is processed by `handleStatus()`:
   - Looks up the existing `WhatsappMessage` by `whatsapp_message_id`
   - Updates the status and sets the corresponding timestamp (`sent_at`, `delivered_at`, `read_at`, `whatsapp_deleted_at`)
   - Logs a warning if the message is not found in the database

<p align="right"><em><a href="#table-of-contents">back to top</a></em></p>

## Run and Test

We included a sample fresh Laravel app to help those who want to contribute to the package. To start it just go through the following steps:

1. Clone this repository.
2. Run `composer setup` from the repository root. It enters the sample app, installs dependencies, creates `.env` when missing, generates the app key, starts Sail, waits for MySQL, runs migrations, and returns to the root folder.
3. Run `composer start` from the repository root. It will run the sample app through `laravel sail`.
4. With the local server running, from another terminal run `composer connect` from the repository. It will connect to [pinggy](https://pinggy.io) service and expose your local application to the web.
5. Adjust your webhook configuration on the Meta/Facebook dashboard with the generated [pinggy](https://pinggy.io) URI.

## Tooling

There are a few tools we provide alongside with the source code to ease a little bit the burden of following a bunch of patterns and standards we set up, as well as automate some boring processes.

### Comitizen

This CLI helps with writting commit messages in a meaningful and standardized way, so that our automation process can use them to properly write our software changelog.

If you like the tool, [kudo the devs](https://github.com/lintingzhen/commitizen-go) ;)

#### How to use Comitzen

Just run `composer commit` from the repository's root folder instead of the traditional `git commit` and follow the CLI interactive steps :)

### Pinggy

This service allows routing external requests to your local environment. You can use it to test your Watsapp webooks locally while developing.

If you like the tool, [kudos the devs](https://pinggy.io) ;)

#### How to use Pinggy

Just run `composer connect` from the repository's root folder. You will need to have our sample application running so you can receive webhook requests locally.
After running the composer command, update your [Meta Developer Dashboard](https://developers.facebook.com/). Follow the process on [Meta Dashboard Setup](#meta-dashboard-setup) section of this README.

## Contributors

Kudos to all our dear contributors. Without them, nothing would have been possible :heart:

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tbody>
    <tr>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/rafapaulin"><img src="https://avatars.githubusercontent.com/u/13452406?v=4?s=60" width="60px;" alt="Rafael Eduardo Paulin"/><br /><sub><b>Rafael Eduardo Paulin</b></sub></a><br /><a href="https://github.com/42dx/whatsapp-laravel-sdk/commits?author=rafapaulin" title="Code">💻</a> <a href="#design-rafapaulin" title="Design">🎨</a> <a href="https://github.com/42dx/whatsapp-laravel-sdk/commits?author=rafapaulin" title="Documentation">📖</a> <a href="#ideas-rafapaulin" title="Ideas, Planning, & Feedback">🤔</a> <a href="#infra-rafapaulin" title="Infrastructure (Hosting, Build-Tools, etc)">🚇</a> <a href="#maintenance-rafapaulin" title="Maintenance">🚧</a> <a href="#projectManagement-rafapaulin" title="Project Management">📆</a> <a href="https://github.com/42dx/whatsapp-laravel-sdk/pulls?q=is%3Apr+reviewed-by%3Arafapaulin" title="Reviewed Pull Requests">👀</a> <a href="https://github.com/42dx/whatsapp-laravel-sdk/commits?author=rafapaulin" title="Tests">⚠️</a> <a href="#tool-rafapaulin" title="Tools">🔧</a> <a href="#tutorial-rafapaulin" title="Tutorials">✅</a></td>
    </tr>
  </tbody>
</table>
<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->
<!-- ALL-CONTRIBUTORS-LIST:END -->

Would you like to see your profile here? Take a look on our [Code of Conduct](https://github.com/42dx/.github/blob/main/CODE_OF_CONDUCT.md) and our [Contributing](https://github.com/42dx/.github/blob/main/CONTRIBUTING.md) docs, and start coding! We would be thrilled to review a PR of yours! :100:

<p align="right"><em><a href="#table-of-contents">back to top</a></em></p>

## Changelog

All changes made to this package since the start of development can be found either on our [release list](https://github.com/42dx/whatsapp-laravel-sdk/releases) or on the [changelog](CHANGELOG.md).

<p align="right"><em><a href="#table-of-contents">back to top</a></em></p>

## Roadmap

Any planned enhancement to the package will be described and tracked in our [project page](https://github.com/orgs/42dx/projects/3)

<p align="right"><em><a href="#table-of-contents">back to top</a></em></p>
