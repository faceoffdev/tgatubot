<?php

namespace App\Bot\Conversations;

use App\Bot\Actions\CreateOrderAction;
use App\Bot\Enums\Buttons\CommonButton;
use App\Bot\Enums\Buttons\OrderButton;
use App\Bot\Queries\AccountQueries;
use App\Bot\Queries\QuestionQueries;
use App\Common\Helper\QuestionHelper;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Facades\Auth;
use Psr\Log\LoggerInterface;

class OrderConversation extends Conversation
{
    public function __construct(protected array $questionIds)
    {
    }

    public static function getKeyboard(): Keyboard
    {
        return (new Keyboard())
            ->addRow(KeyboardButton::create(__('buttons.order.pay'))->callbackData(OrderButton::PAY->value))
            ->addRow(KeyboardButton::create(__('buttons.common.menu'))->callbackData(CommonButton::MENU->value));
    }

    public function run()
    {
        $questionHelper = app(QuestionHelper::class);
        $questions      = (new QuestionQueries())->findByIds($this->questionIds, ['id', 'name', 'price']);
        $price          = $questions->sum('price');

        $text = '';

        foreach ($questions as $question) {
            $text .= sprintf('[• %s](%s)' . PHP_EOL, $question->name, $questionHelper->getUrl($question->id));
        }

        $this->ask(
            __('questions.order.pay', ['items' => $text, 'price' => $price]),
            fn (Answer $answer) => $this->runHandler($answer, $price),
            ['parse_mode'       => 'markdown', ...self::getKeyboard()->toArray()]
        );
    }

    private function runHandler(Answer $answer, float $price)
    {
        $value = $answer->isInteractiveMessageReply() ? $answer->getValue() : CommonButton::MENU->value;

        if ($value === CommonButton::MENU->value) {
            $this->bot->startConversation(new MainConversation());

            return;
        }

        $user           = Auth::user();
        $accountQueries = new AccountQueries();

        if ($accountQueries->notExistsLoginOrPassword($user->id)) {
            $this->bot->reply(__('errors.validation.account'));
            $this->bot->startConversation(new SettingsConversation());

            return;
        }

        if ($user->computed_info->money < $price) {
            $this->bot->sendRequest('answerCallbackQuery', [
                'callback_query_id' => $answer->getCallbackId(),
                'show_alert'        => true,
                'text'              => __('errors.wallet.not_enough_money', [
                    'price' => $price,
                    'money' => $user->computed_info->money,
                ]),
            ]);

            $this->bot->startConversation(new WalletTopUpConversation());

            return;
        }

        $account = $accountQueries->getFirstByUserId($user->id, ['id']);
        $orderId = (new CreateOrderAction(app(LoggerInterface::class)))
            ->execute($user->id, $account->id, $price, $this->questionIds);

        $params = (new Keyboard())
            ->addRow(KeyboardButton::create(__('buttons.common.support'))
                ->url(config('botman.telegram.support_url')))
            ->toArray();

        if ($orderId) {
            $this->bot->reply(__('success.order.create', ['num' => $orderId]), $params);
        } else {
            $this->bot->reply(__('errors.order.create'), $params);
        }
    }
}
