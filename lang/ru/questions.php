<?php

return [
    'main' => [
        'ask' => 'Главное меню 📃',
    ],

    'referrals' => '👥 Привлеките клиента один раз и получайте вознаграждение за все его заказы!' . PHP_EOL
        . 'Платим *:percent%* от суммы заказа.' . PHP_EOL . PHP_EOL
        . 'Ваша реферальная ссылка:' . PHP_EOL
        . '[t.me/tgatu_bot?start=:id](t.me/tgatu_bot?start=:id)' . PHP_EOL . PHP_EOL
        . 'Вы пригласили: *:count*' . PHP_EOL
        . 'Ваш заработок: *:money* UAH',

    'wallet' => [
        'balance'   => '*💸 Кошелёк*' . PHP_EOL . PHP_EOL . 'Баланс: *:money* UAH',
        'withdraws' => PHP_EOL . PHP_EOL . 'Активные заявки на вывод:' . PHP_EOL,
        'withdraw'  => '· №:num: *:money* UAH' . PHP_EOL,
        'top_up'    => '*➕ Пополнение*' . PHP_EOL . PHP_EOL
            . '❗❗️Обязательно добавьте в комментарий к платежу: `:id`' . PHP_EOL . PHP_EOL
            . ':send_url' . PHP_EOL . PHP_EOL . 'Средства будут зачислены в течение 2 минут.',
        'sponsor' => PHP_EOL . PHP_EOL . '--' . PHP_EOL
            . 'Если Вы не владеете картой монобанк, тогда [регистрируйтесь](:url)!'
            . PHP_EOL . 'Регистрируясь по ссылке выше Вы получите *:money* гривен на счет кешбека',
        'ask_url' => 'Введите пригласительную ссылку. ' . PHP_EOL . PHP_EOL
            . '❗Ссылка будет активна, пока не разместит кто-то другой.',
        'ask_money' => 'Введите количество UAH',
        'ask_card'  => 'Введите номер карты украинского банка. ' . PHP_EOL . PHP_EOL
            . 'Комиссия за вывод составляет: :commission UAH',
    ],

    'settings' => [
        'ask_login'    => 'Введите логин от учетной записи',
        'ask_password' => 'Введите пароль от учетной записи',
    ],

    'questions' => [
        'ask_course'     => 'Выберите курс',
        'ask_group'      => 'Выберите группу',
        'ask_discipline' => 'Выберите дисциплину',
        'ask_type'       => 'Выберите тип',

        'single' => [
            'ask_question' => 'Выберите тест',
        ],

        'mass' => [
            'ask_action' => 'Что пройти?' . PHP_EOL . PHP_EOL
                . 'Важно! Тесты без ПМК, для прохождения ПМК воспользуйтесь ручным выбором.',
        ],
    ],

    'order' => [
        'pay' => '*Заказ*' . PHP_EOL . PHP_EOL . ':items' . PHP_EOL . 'Итого к оплате: *:price* UAH' . PHP_EOL . PHP_EOL
            . 'Нажмите Оплатить, если все верно указано.',
        'pay_with_discount' => '*Заказ*' . PHP_EOL . PHP_EOL . ':items' . PHP_EOL . 'Итого к оплате: *:price* UAH'
            . ', с учетом скидки *:discount%*' . PHP_EOL . PHP_EOL
            . 'Нажмите Оплатить, если все верно указано.',
    ],
];
