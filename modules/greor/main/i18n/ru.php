<?php defined('SYSPATH') or die('No direct script access.');

return array(
//	validation errors
	':field must contain only letters' => 'Поле \':field\' может содержать только латинские буквы',
	':field must contain only numbers, letters and dashes' => 'Поле \':field\' может содержать только латинские буквы, числа, дефисы и знаки подчекивания',
	':field must contain only letters and numbers' => 'Поле \':field\' может содержать только латинские буквы и цифры',
	':field must be a color' => 'Поле \':field\' должно содержать код цвета',
	':field must be a credit card number' => 'Поле \':field\' должно быть номером кредитной карты',
	':field must be a date' => 'Поле \':field\' должно быть датой',
	':field must be a decimal with :param2 places' => '',
	':field must be a digit' => 'Поле \':field\' может содержать только цифры (без разделителей)',
	':field must be a email address' => 'Поле \':field\' должно быть адресом e-mail',
	':field must contain a valid email domain' => 'Поле \':field\' должно содержать валидный домен почты',
	':field must equal :param2' => 'Поле \':field\' должно быть равно :param2',
	':field must be exactly :param2 characters long' => 'Длинна \':field\' должна быть равно :param2 символов',
	':field must be one of the available options' => '\':field\' должно быть одним из предложенных вариантов',
	':field must be an ip address' => 'Поле \':field\' должно быть ip-адресом',
	':field must be the same as :param2' => 'Поле \':field\' должно быть тем же что и :param2',
	':field must be at least :param2 characters long' => 'Поле \':field\' должно содержать минимум :param2 символов',
	':field must not exceed :param2 characters long' => 'Поле \':field\' не должно превышать :param2 символов',
	':field must not be empty' => 'Поле \':field\' обязательно для заполнения',
	':field must be numeric' => 'Поле \':field\' должно быть числом',
	':field must be a phone number' => 'Поле \':field\' должно быть корректным номером телефона',
	':field must be within the range of :param2 to :param3' => '\':field\' должно быть в диапазоне от :param2 до :param3',
	':field does not match the required format' => 'Поле \':field\' не соответствует требуемому формату',
	':field must be a url' => 'Поле \':field\' должно быть корректным адресом url',
	'Entered :field already exist' => 'Введеный \':field\' уже существует',
	':field must contain special symbols (-_,./<>?:;[]{}~!@#$%^&*()+|=\№)' => "Поле ':field' должно содержать специальные символы (-_,./<>?:;[]{}~!@#$%^&*()+|=\№), цифры, символы латинского альфавита и начинаться с буквы",
	':field must contain special symbols' => "Поле ':field' должно содержать символ(-ы) подчеркивания/дефиса, цифры, символы латинского альфавита и начинаться с буквы",
	'\'password confirm\' must be equal :field' => 'Неверное подтверждение пароля',
	'Set massage theme' => 'Выберете тему сообщения',
	"':field' must not relate to oneself" => "':field' не может ссылаться на себя",
	"Element already exist. Element mast has unique name, and unique pair 'Parent page'-'URI'" => "Элемент существует. Элемент должен иметь уникальное имя и уникальную пару значений 'Страница-родитель'-'URI'",
	"An element with the same URI exists" => "Элемент с таким URI существует",

	'Page type value must be input' => 'Дополнительные параметры типа страницы должны быть заполнены',
	'Time must have 24-hour format.' => 'Время должно быть указано в 24-часовом формате.',
	'Time must have 12-hour format.' => 'Время должно быть указано в 12-часовом формате.',
	'Invalid code' => 'Неверный код',
	'Page with this url already exist' => 'Страница с таким URL уже существует',
	'Page type value must be input.' => 'Страница не может ссылаться сама на себя, либо некорректный url',
	'Only one site with type "master" can exist' => 'Федеральный сайт может быть только один',
	'Authentication error' => 'Ошибка авторизации',
	'To many failed login attempts. Please, wait :minutes minutes and try again.' => 'Слишком много неудачных попыток входа. Пожалуйста, подождите :minutes минут(ы).',
	'Not Found' => 'Не Найдено',
	'Page Not found' => 'Страница Не Найдена',
	'Internal Server Error' => 'Внутренняя Ошибка Сервера',
	'Maintenance Mode' => 'Сервис Недоступен',
	'404 Not found' => '404 Не Найдено',
	'404 Page Not found' => '404 Страница Не Найдена',
	'500 Internal Server Error' => '500 Внутренняя Ошибка Сервера',
	'503 Maintenance Mode' => '503 Сервис Недоступен',



	'Authentication' => 'Аутентификация',
	'Login' => 'Логин',
	'Password' => 'Пароль',
	'Remember me' => 'Запомнить',
	'Sign in' => 'Войти',
	'Administrative Interface' => 'Административный интерфейс',

	'Site structure' => 'Структура сайта',
	'Add page' => 'Добавить страницу',
	'Edit page' => 'Редактировать страницу',
	'Fix positions' => 'Фиксировать позиции',
	'Clear structure cache' => 'Очистить кеш структуры',
	'Static' => 'Статическая',
	'Module' => 'Модуль',
	'Redirect to page' => 'Ссылка на страницу',
	'Link' => 'Ссылка',
	'Active page' => 'Активная страница',
	'Inactive page' => 'Неактивная страница',
	'Hidden page' => 'Скрытая страница',
	'Pages list' => 'Список страниц',
	'Can hiding' => 'Может быть скрыта регионами',
	'MMT' => 'Время относительно Москвы',
	'Active' => 'Активность',
	'Position' => 'Позиция',
	'Parent page' => 'Страница-родитель',
	'Page type' => 'Тип странницы',
	'Page status' => 'Статус страницы',
	'Keywords' => 'Ключевые слова',
	'Description' => 'Описание',
	'Title'	=> 'Заголовок',
	'Desription'	=> 'Описание',
	'Announcement' => 'Анонс',
	'Text' => 'Текст',
	'URI' => 'URI',
	'URL' => 'URL',
	'URI segment' => 'Сегмент URI',
	'Additional params' => 'Дополнительные параметры',
	'Title tag' => 'Мета-тэг "title"',
	'Keywords tag' => 'Мета-тэг "keywords"',
	'Desription tag' => 'Мета-тэг "desription"',
	'For all sites' => 'Для всех сайтов',
	'Save and Exit' => 'Сохранить и Выйти',
	'Save and Add' => 'Сохранить и Добавить',
	'Cancel' => 'Отмена',
	'Save' => 'Сохранить',
	'Edit' => 'Редактировать',
	'Delete' => 'Удалить',
	'Open link' => 'Открыть ссылку',
	'_self' => 'в том же окне',
	'_blank' => 'в новом окне',
	'_modal' => 'в модальном окне',

	'Admin list' => 'Список администраторов',
	'Add admin' => 'Добавить администратора',
	'Edit admin' => 'Редактировать администратора',
	'Users' => 'Пользователи',
	'Site' => 'Сайт',
	'Role' => 'Роль',
	'Password confirm' => 'Подтверждение пароля',
	'Logins' => 'Всего входов',
	'Last login' => 'Последний вход',
	'Move up' => 'На позицию вверх',
	'Move down' => 'На позицию вниз',
	'Show' => 'Отобразить',
	'Hide' => 'Скрыть',
	'Deactivate' => 'Деактивировать',
	'Activate' => 'Активировать',
	'Actions' => 'Действия',
	'Modules' => 'Модули',
	'Settings' => 'Настройки',
	'Site manager' => 'Управление сайтами',
	'Sites list' => 'Список сайтов',
	'Add site' => 'Добавить сайт',
	'Edit site' => 'Редактировать сайт',
	'Name' => 'Имя',
	'Type' => 'Тип',
	'Sites' => 'Сайты',
	'Master site' => 'Федеральный сайт',
	'Full version' => 'Полная версия',
	'Base version' => 'Базовая версия',
	'Logo' => 'Логотип',
	'Sharing image' => 'Sharing (изображение)',
	'Kaliningrad time (MSK–1)' => 'Калининградское время (MSK–1)',
	'Moscow Time (MSK)' => 'Московское время (MSK)',
	'Yekaterinburg time (MSK+2)' => 'Екатеринбургское время (MSK+2)',
	'Omsk time (MSK+3)' => 'Омское время (MSK+3)',
	'Krasnoyarsk time (MSK+4)' => 'Красноярское время (MSK+4)',
	'Irkutsk time (MSK+5)' => 'Иркутское время (MSK+5)',
	'Yakut time (MSK+6)' => 'Якутское время (MSK+6)',
	'Vladivostok time (MSK+7)' => 'Владивостокское время (MSK+7)',
	'Magadan time (MSK+8)' => 'Магаданское время (MSK+8)',

	'Home page' => 'Главная',
	'Separate file' => 'В отдельном файле',
	'Select item' => 'Выберете элемент',
	'Add item' => 'Добавить элемент',
	'Edit item' => 'Редактировать элемент',
	'new item' => 'новый элемент',
	'Code' => 'Код',
	'Properties' => 'Свойства',
	'Value' => 'Значение',
	'Main' => 'Основное',
	'New site' => 'Новый сайт',
	'Delete file' => 'Удалить файл',
	'Open in new window' => 'Открыть в новом окне',
	'List' => 'Список',
	'Page' => 'Страница',
	'Move first' => 'В начало списка',
	'Move last' => 'В конец списка',
	'Show' => 'Отобразить',
	'Hide' => 'Скрыть',
	'Image' => 'Изображение',
	'Delete image' => 'Удалить изображение',
	'Date' => 'Дата',
	'Public date' => 'Дата публикации',
	'Close date' => 'Дата завершения',
	'all' => 'все',
	'own' => 'свои',
	'No image' => 'Нет изображения',
	'no image' => 'нет изображения',
	'No text' => 'Нет текста',
	'no text' => 'нет текста',
	'Back' => 'Вернуться',
	'edition' => 'редактирование',
	'new category' => 'новая категория',
	'High activity from your ip. Try vote later.' => 'Высокая активность с вашего ip. Попробуйте проголосовать позже.',
	'You have successfully voted.' => 'Вы успешно проголосовали.',
	'You have already voted!' => 'Ты уже голосовал!',
	
	'Forms' => 'Формы',
	'Forms (structure)' => 'Формы (структура)',
	'Forms (Responses)' => 'Формы (сообщения)',
	'Add form' => 'Добавить форму',
	'Edit form' => 'Редактировать форму',
	'Captcha' => 'Защитный код',
	'Send to (e-mail)' => 'Отправить (эл. адрес)',
	'Display text on top' => 'Отображать текст сверху',
	'Fields' => 'Поля',
	'Add field' => 'Добавить поле',
	'Add standard fields' => 'Добавить стандартные поля',
	'Full name, city, email, phone' => 'Полное имя, город, эл. адрес, номер телефона',
	'Delete all fields' => 'Удалить все поля',
	'Select filed type' => 'Выберете тип поля',
	'Text field' => 'Текстовое поле',
	'Textarea field' => 'Текстовое поле (многостроковое)',
	'Checkbox field' => 'Чекбокс',
	'Select field' => 'Выпадающий список',
	'Date field' => 'Дата',
	'Counter field' => 'Счетчик',
	'Messages' => 'Сообщения',
	'Form' => 'Форма',
	'Created' => 'Дата создания',
	'View' => 'Просмотреть',
	'Mark as read' => 'Отметить как прочитанное',
	'Message' => 'Сообщение',
	'Sended to' => 'Копия сообщения',
	'Favorite' => 'Избранное',
	'Singer' => 'Исполнитель',
	'Songs' => 'Композиции',
	'Start time' => 'Время начала',
	'End time' => 'Время окончания',
	'Start' => 'Начало',
	'End' => 'Окончание',
	'Config' => 'Настройки',
	'Mode' => 'Режим работы',
	'Embed code' => 'Код вставки',
	
	'yes' => 'да',
	'Yes' => 'Да',
	'no' => 'нет',
	'No' => 'Нет',
	
	'Common' => 'Общий',
	'Status' => 'Статус',
	'status inactive' => 'неактивно',
	'status hidden' => 'скрыто',
	'status active' => 'активно',
	'Unnamed' => 'Без названия',
	
	'Preview link' => 'Ссылка превью',
	'Categories' => 'Категории',
	'Category' => 'Категория',
	'Add category' => 'Добавить категорию',
	'Edit category' => 'Редактировать категорию',
	'Open' => 'Открыть',
	'Open category' => 'Открыть категорию',
	' - No category - ' => ' - Без категории - ',
	'Search by title' => 'Поиск по заголовку',
	'Viewing' => 'Просмотр',
	'viewing' => 'просмотр',
);