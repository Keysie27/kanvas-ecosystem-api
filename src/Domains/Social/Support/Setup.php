<?php

declare(strict_types=1);

namespace Kanvas\Social\Support;

use Baka\Contracts\AppInterface;
use Baka\Contracts\CompanyInterface;
use Baka\Users\Contracts\UserInterface;
use Exception;
use Illuminate\Support\Str;
use Kanvas\Notifications\Actions\CreateNotificationTypeAction;
use Kanvas\Notifications\Actions\CreateNotificationTypesMessageLogicAction;
use Kanvas\Notifications\DataTransferObject\NotificationType;
use Kanvas\Notifications\Enums\NotificationChannelEnum;
use Kanvas\Notifications\Models\NotificationChannel;
use Kanvas\Notifications\Repositories\NotificationTypesRepository;
use Kanvas\Social\Enums\StateEnums;
use Kanvas\Social\Follows\Actions\FollowAction;
use Kanvas\Social\Interactions\Actions\CreateInteraction;
use Kanvas\Social\Interactions\DataTransferObject\Interaction;
use Kanvas\Social\Interactions\Models\Interactions;
use Kanvas\Social\MessagesTypes\Actions\CreateMessageTypeAction;
use Kanvas\Social\MessagesTypes\DataTransferObject\MessageTypeInput;
use Kanvas\SystemModules\Actions\CreateInCurrentAppAction;
use Kanvas\Templates\Actions\CreateTemplateAction;
use Kanvas\Templates\DataTransferObject\TemplateInput;
use Kanvas\Users\Actions\CreateUserLinkedSourcesAction;
use Kanvas\Users\Models\Sources;
use Kanvas\Users\Repositories\SourcesRepository;

class Setup
{
    /**
     * Constructor.
     */
    public function __construct(
        protected AppInterface $app,
        protected UserInterface $user,
        protected CompanyInterface $company
    ) {
    }

    /**
     * Setup all the default inventory data for this current company.
     */
    public function run(): bool
    {
        $createSystemModule = new CreateInCurrentAppAction($this->app);
        // $createSystemModule->execute(Interactions::class);
        (new CreateSystemModule($this->app))->run();

        $createInteractions = new CreateInteraction(
            new Interaction(
                (string) StateEnums::LIKE->getValue(),
                $this->app,
                ucfirst((string) StateEnums::LIKE->getValue()),
            )
        );

        $defaultInteraction = $createInteractions->execute();

        $createInteractions = new CreateInteraction(
            new Interaction(
                (string) StateEnums::DISLIKE->getValue(),
                $this->app,
                ucfirst((string) StateEnums::DISLIKE->getValue()),
            )
        );

        $defaultInteraction = $createInteractions->execute();

        $createInteractions = new CreateInteraction(
            new Interaction(
                (string) StateEnums::SAVE->getValue(),
                $this->app,
                ucfirst((string) StateEnums::SAVE->getValue()),
            )
        );

        $defaultInteraction = $createInteractions->execute();
        $createInteractions = new CreateInteraction(
            new Interaction(
                (string) StateEnums::REACTION->getValue(),
                $this->app,
                ucfirst((string) StateEnums::REACTION->getValue()),
            )
        );

        $defaultInteraction = $createInteractions->execute();
        $createInteractions = new CreateInteraction(
            new Interaction(
                (string) StateEnums::FOLLOW->getValue(),
                $this->app,
                ucfirst((string) StateEnums::FOLLOW->getValue()),
            )
        );

        $defaultInteraction = $createInteractions->execute();
        $createInteractions = new CreateInteraction(
            new Interaction(
                (string) StateEnums::COMMENT->getValue(),
                $this->app,
                ucfirst((string) StateEnums::COMMENT->getValue()),
            )
        );

        $defaultInteraction = $createInteractions->execute();
        $createInteractions = new CreateInteraction(
            new Interaction(
                (string) StateEnums::SHARE->getValue(),
                $this->app,
                ucfirst((string) StateEnums::SHARE->getValue()),
            )
        );

        $defaultInteraction = $createInteractions->execute();

        $createFollow = new FollowAction(
            $this->user,
            $this->user,
            $this->company,
        );

        $createFollow->execute();

        // $source = SourcesRepository::getByTitle('apple');
        $source = new Sources();
        $source->title = (string)Str::random(6);
        $source->url = (string)Str::random(6);
        $source->language_id = 1;
        $source->created_at = date('Y-m-d H:i:s');
        $source->is_deleted = 0;
        $source->saveOrFail();


        $createUserLinkedSource = new CreateUserLinkedSourcesAction(
            $this->user,
            $source,
            (string)Str::uuid(),
        );

        $createUserLinkedSource->execute();

        $messageTypeInput = MessageTypeInput::from([
            'apps_id' => $this->app->getId(),
            'languages_id' => 1,
            'name' => 'entity',
            'verb' => 'entity',
            'template' => '',
            'template_plura' => '',
        ]);

        $createMessageType = new CreateMessageTypeAction($messageTypeInput);
        $messageType = $createMessageType->execute();

        try {
            $notificationType = $this->createTestNotificationTypeForMessages();
            $logic = '{"conditions": "message.is_public == 1 and message.is_published == 1"}';

            $createNotificationTypeMessageLogic = new CreateNotificationTypesMessageLogicAction(
                $this->app,
                $notificationType,
                $logic
            );

            $createNotificationTypeMessageLogic->execute();
        } catch(Exception $e) {
        }



        return $defaultInteraction instanceof Interactions;
    }

    public function createTestNotificationTypeForMessages()
    {
        $createParentTemplate = new CreateTemplateAction(
            TemplateInput::from([
                'app' => $this->app,
                'name' => 'test-notification',
                'template' => '<html><body>Hello this is a test notification with {{ isset($dynamic) ? $dynamic : \'default value\' }} values</body></html>',
                ])
        );
        $template = $createParentTemplate->execute();

        $createPushTemplate = new CreateTemplateAction(
            TemplateInput::from([
                'app' => $this->app,
                'name' => 'test-notification-push',
                'template' => '{"message" : "Hello this is a test notification with {{ isset($dynamic) ? $dynamic : \'default value\' }} values"}',
                ])
        );
        $pushTemplate = $createPushTemplate->execute();

        $notificationType = (new CreateNotificationTypeAction(
            new NotificationType(
                $this->app,
                $this->user,
                'test-notification-message',
                'test-notification-message',
                $template
            )
        ))->execute();

        $notificationType->assignChannel(
            NotificationChannel::getById(NotificationChannelEnum::MAIL->value),
            $template
        );

        $notificationType->assignChannel(
            NotificationChannel::getById(NotificationChannelEnum::PUSH->value),
            $pushTemplate
        );

        return $notificationType;
    }
}
