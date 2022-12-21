<?php

namespace App\Jobs;

use App\Factories\NotificationFactory;
use App\Models\Notification;
use App\Repositories\NotificationRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param int[] $notificationIds
     */
    public function __construct(private readonly array $notificationIds)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $notificationRepository = app(NotificationRepositoryInterface::class);

        $notifications = $notificationRepository->get($this->notificationIds);

        /** @var Notification $notification */
        foreach ($notifications as $notification) {
            /** @var NotificationFactory $factory */
            $factory = app(NotificationFactory::class, ['notification' => $notification]);
            $notifier = $factory->create();
            $notifier->execute();

            $notification->completed_at = now();
            $notification->save();
            echo sprintf('Notification id:%s has been handled!%s', $notification->id, PHP_EOL);
        }

        echo sprintf('Notifications are handled!%s', PHP_EOL);
    }
}
