<?php

return [
    'wallet' => [
        'pay'            => '💰 Успешное пополнение. Вы получили :amount UAH',
        'pay_url'        => 'Успешно! Желаю побольше рефералов :)',
        'withdraw_money' => '*Заявка №:num создана 💸*' . PHP_EOL . PHP_EOL
            . '*:money* UAH будут зачислены на карту :card в течение суток.' . PHP_EOL
            . 'Комиссия: *:commission* UAH',
    ],

    'order' => [
        'create' => '✅ Заказ №:num успешно создан. После выполнения Вам обязательно придет уведомление.' . PHP_EOL
            . PHP_EOL
            . '‼️ Убедительная просьба не использовать свой аккаунт на портале до завершения прохождения тестов.'
            . ' В этом случае средства не возвращаются.'
            . PHP_EOL,
        'notify' => 'Заказ №:num выполнен.' . PHP_EOL . PHP_EOL . 'Пройденные тесты:' . PHP_EOL . ':questions',
    ],
];
