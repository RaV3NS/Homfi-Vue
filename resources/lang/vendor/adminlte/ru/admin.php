<?php

return [
    'button' => [
        'block' => 'Заблокировать',
        'unblock' => 'Разблокировать',
        'activate' => 'Активировать',
        'enable' => 'Активировать',
        'disable' => 'Деактивировать',
        'edit' => 'Редактировать',
        'save' => 'Сохранить',
        'delete' => 'Удалить',
        'show' => 'Просмотр',
        'confirm' => 'Подтвердить',
        'reject' => 'Отклонить',
        'moderate' => 'Модерировать',
        'cancel' => 'Отмена',
        'close' => 'Закрыть',
        'hide' => 'Скрыть',
        'confirm_hide' => 'Вы действительно хотите скрыть этот район?',
        'show_admin' => 'Отобразить',
        'confirm_show_admin' => 'Вы действительно хотите отобразить этот район?',
    ],

    'user' => [
        'blocked_reason' => 'Причина блокировки',
        'active_reason' => 'Причина разблокировки',
        'deleted_reason' => 'Причина удаления',
        'disabled_reason' => 'Причина деактивации',
        'reason' => [
            'want_money' => 'Взымание платы за посреднические услуги',
            'wrong_info' => 'Размещение объявления с недостоверной информацией',
            'mass_complains' => 'Массовые жалобы клиентов',
            'another_reason' => 'Другая причина',
            'another_placeholder' => 'Укажите причину',
        ],
        'validation' => [
            'required' => 'Необходимо указать причину',
            'title' => 'Заполните необходимые поля',
            'string' => 'Необходимо указать причину',
        ],
        'event' => [
            'active' => 'Активировал аккаунт',
            'blocked' => 'Заблокировал аккаунт',
            'deleted' => 'Удалил аккаунт',
            'disabled' => 'Деактивировал аккаунт',
        ]
    ],

    'complain' => [
        'status' => [
            'pending' => 'Ожидающая',
            'rejected' => 'Отклонена',
            'solved' => 'Решена',
        ]
    ],

    'advert' => [
        'blocked_reason' => 'Причина блокировки',
        'advert_blocked_reason' => 'Причина блокировки',
        'advert_activate_reason' => 'Причина разблокировки',
        'advert_enabled_reason' => 'Причина разблокировки',
        'advert_rejected_reason' => 'Причина отказа',
        'advert_accepted_reason' => '',
        'reason' => [
            'owner_request' => 'Обращение владельца',
            'dis_photo' => 'Несоответствие фото',
            'dis_price' => 'Несоответствие цены',
            'dis_address' => 'Несоответствие адреса',
            'not_actual' => 'Объявление не актуально сдано/снято с рынка',
            'wrong_contacts' => 'Неверная контактная информация ',
            'fraud' => 'Мошенничество',
            'another' => 'Другая причина',
            'another_placeholder' => 'Укажите причину',
            'bad_photos' => 'Фотографии не соответствуют требованиям',
            'object_done' => 'Объект сдан',
            'contacts_dont_respond' => 'Невозможность связаться по контактным данным',
            'comment' => 'Комментарий',
        ],
        'event' => [
            'advert_active' => 'Активировал объявление',
            'advert_blocked' => 'Заблокировал объявление',
            'advert_enabled' => 'Активировал объявление',
            'advert_rejected' => 'Отклонил объявление на модерации',
            'advert_accepted' => 'Подтвердил объявление на модерации',
        ],
        'unblock_question' => 'Вы уверены, что хотите разблокировать объявление?',
        'reject_description' => 'Отказ в модерации',
    ],
    'advert_type' => [
        'flat' => 'Квартира',
        'house' => 'Дом',
        'half-house' => 'Пол дома',
        'room' => 'Комната',
    ],
    'advert_status' => [
        'enabled' => 'Активно',
        'disabled' => 'Неактивно',
        'moderate' => 'На модерации',
        'deleted' => 'Удалено',
        'accepted' => 'Подтверждено',
        'rejected' => 'Отклонено',
        'draft' => 'Черновик',
        'blocked' => 'Заблокировано',
    ],
    'no_adverts' => 'Нет объявлений',

    'geo' => [
        'district' => 'Область',
        'region' => 'Район',
        'city' => 'Город',
    ],
    'price_for_month' => 'Цена/месяц',
    'communal_payments' => 'Коммунальные платежи',

    'gallery' => 'Галерея',
    'image' => 'Изображение',
    'title' => 'Заголовок',
    'type' => 'Тип',
    'city' => 'Город',
    'address' => 'Адрес',
    'subway' => 'Метро',
    'administrative' => 'Район',
    'yes' => 'Да',
    'no' => 'Нет',
    'created' => 'Дата создания',
    'updated' => 'Дата изменения',
    'body' => 'Текст',
    'search' => 'Поиск',

    'user_adverts_title' => 'Объявления пользователя',
    'block_user_description' => 'Заблокировать пользователя и все его объявления',
    'unblock_user_description' => 'Разблокировать пользователя и все его объявления',
    'activate_user_description' => "Активировать пользователя",
    'deleted_user_description' => "Аккаунт пользователя был удален",

    'advert_profile_title' => 'Страница объявления',
    'edit_advert_profile_title' => 'Страница объявления: Редактирование',
    'edit_advert' => 'Редактирование объявления',
    'deleted_advert_description' => 'Объявление было удалено пользователем',
    'advert_gallery_title' => 'Галерея изображений',

    'advert_complains_title' => 'Жалобы пользователей',
    'unblock_advert_description' => 'Разблокировать объявление',
    'block_advert_description' => 'Заблокировать объявление',

    'user_updated_successfully' => "Пользователь успешно обновлен",
    'cant_update_user' => "Невозможно обновить пользователя",

    'user_profile' => 'Пользователь',
    'full_name' => 'Фамилия, Имя',
    'last_name' => 'Фамилия',
    'first_name' => 'Имя',
    'phones' => 'Телефоны',
    'register_date' => 'Дата регистрации',
    'last_login' => 'Последний логин',
    'general' => 'Общие',
    'adverts' => 'Объвления',
    'history' => 'История',
    'user_profile_title' => 'Профиль пользователя',
    'social_nets_title' => 'Социальные сети',
    'phone_is_main' => 'Основной телефон',

    'users' => 'Пользователи',
    'complains' => 'Жалобы',
    'notifications' => 'Уведомления',
    'content' => 'Контент',
    'dashboard_title' => 'Админ Панель',
    'registration_date' => 'Дата регистрации',
    'email_confirmed' => 'Email подтвержден',
    'status' => 'Статус',
    'active' => 'Активен',
    'disabled' => 'Неактивен',
    'blocked' => 'Заблокирован',
    'deleted' => 'Удален',
    'last_login_date' => 'Последний логин',
    'actions' => 'Действия',
    'no_users' => 'Нет пользователей',
    'no_results' => 'К сожалению, Ваш запрос не дал результатов. Измените параметры фильтра и попробуйте еще раз',
    'showing_info_dt' => "Показано с _START_ по _END_ из _TOTAL_ записей",
    'showing_info_dt_zero' => "Показано с 0 по 0 из 0 записей",
    'dt_first' => "Первая",
    'dt_last' => "Последняя",
    'dt_next' => "Следующая",
    'dt_prev' => "Предыдущая",
    'dt_menu_title' => "_MENU_ строк",

    'history_title_registered_user' => "Пользователь зарегестрировался",
    'user_active_status' => "Статус: Активен",
    'user_notactive_status' => "Статус: Неактивен",
    'user_block_status' => "Статус: Заблокирован",
    'user_deleted_status' => "Статус: Удален",
    'advert_notactive_status' => 'Статус: Неактивно',
    'advert_active_status' => 'Статус: Активно',
    'advert_blocked_status' => 'Статус: Заблокировано',
    'advert_rejected_status' => 'Статус: Отклонено',

    'history_title_created_advert' => 'Создание объявления'


];