<?php

namespace App\Order\Actions;

use App\Common\Actions\SayTelegramAction;
use App\Common\Models\Order;
use App\Common\Models\User;
use App\Order\DTOs\NotifyDTO;
use App\Order\Queries\OrderQueries;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Psr\Log\LoggerInterface;

final class SendErrorNotifyAction
{
    private OrderQueries $orderQueries;

    public function __construct(
        private SetOrderCompletedAction $orderCompletedAction,
        private SayTelegramAction $sayTelegramAction,
        private LoggerInterface $logger
    ) {
        $this->orderQueries = new OrderQueries();
    }

    public static function getKeyboard(): Keyboard
    {
        return (new Keyboard())
            ->addRow(KeyboardButton::create(__('buttons.common.support'))
                ->url(config('botman.telegram.support_url')));
    }

    public function execute(NotifyDTO $dto)
    {
        $order = $this->orderQueries->findById($dto->orderId, ['user_id', 'price']);
        $text  = __('errors.order.notify', ['num' => $dto->orderId, 'message' => $this->getMessage($dto->code)]);

        $this->orderCompletedAction->execute($dto->orderId);

        if ($this->refund($dto->code, $order)) {
            $text .= PHP_EOL . PHP_EOL . 'Средства возвращены на баланс.';
        }

        $this->sayTelegramAction->execute(
            $text,
            $order->user_id,
            ['parse_mode' => 'markdown', ...self::getKeyboard()->toArray()]
        );

        if ($dto->message) {
            $this->logger->error($dto->message);
        }
    }

    private function getMessage(int $code): string
    {
        $base = 'errors.order.messages.';

        $tpl = match ($code) {
            1       => $base . 1,
            2       => $base . 2,
            default => $base . 'default'
        };

        return __($tpl);
    }

    private function refund(int $code, Order $order): bool
    {
        if ($code === 1) {
            return false;
        }

        User::whereId($order->user_id)->increment('money', $order->price);

        return true;
    }
}
