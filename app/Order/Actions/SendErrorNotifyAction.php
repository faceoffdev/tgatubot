<?php

namespace App\Order\Actions;

use App\Common\Actions\SayTelegramAction;
use App\Common\Models\Order;
use App\Common\Models\UserComputedInfo;
use App\Order\DTOs\NotifyDTO;
use App\Order\Queries\OrderQueries;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Psr\Log\LoggerInterface;

final class SendErrorNotifyAction
{
    public const ALLOWED_CODES = [1, 2, 3, 4, 5, 6];
    public const REFUND_CODES  = [2, 5, 6];

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

        if (in_array($code, self::ALLOWED_CODES)) {
            return __($base . $code);
        }

        return __($base . 'default');
    }

    private function refund(int $code, Order $order): bool
    {
        if (in_array($code, self::REFUND_CODES)) {
            UserComputedInfo::whereId($order->user_id)->increment('money', $order->price);

            return true;
        }

        return false;
    }
}
