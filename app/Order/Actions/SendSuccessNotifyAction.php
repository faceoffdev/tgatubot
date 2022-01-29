<?php

namespace App\Order\Actions;

use App\Common\Actions\SayTelegramAction;
use App\Common\Helper\QuestionHelper;
use App\Common\Models\Question;
use App\Order\Queries\OrderQueries;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

final class SendSuccessNotifyAction
{
    public const CODE_OK = 0;

    private OrderQueries $orderQueries;

    public function __construct(
        private SetOrderCompletedAction $orderCompletedAction,
        private AddMoneyForReferralAction $addMoneyForReferralAction,
        private QuestionHelper $questionHelper,
        private SayTelegramAction $sayTelegramAction
    ) {
        $this->orderQueries = new OrderQueries();
    }

    public static function getKeyboard(): Keyboard
    {
        return (new Keyboard())
            ->addRow(KeyboardButton::create(__('buttons.common.feedback'))
                ->url(config('botman.telegram.support_url')));
    }

    public function execute(int $id)
    {
        $order         = $this->orderQueries->findById($id, ['id', 'user_id', 'question_ids', 'price']);
        $textQuestions = '';

        foreach ($order->questions as $question) {
            $textQuestions .= $this->getTextQuestion($question);
        }

        $this->sayTelegramAction->execute(
            __('success.order.notify', ['num' => $id, 'questions' => $textQuestions]),
            $order->user_id,
            ['parse_mode' => 'markdown', ...self::getKeyboard()->toArray()]
        );

        $this->orderCompletedAction->execute($id);
        $this->addMoneyForReferralAction->execute($order->user_id, $order->price);
    }

    private function getTextQuestion(Question $question): string
    {
        return sprintf(
            '[• %s](%s)' . PHP_EOL,
            $question->name,
            $this->questionHelper->getUrl($question->id)
        );
    }
}
