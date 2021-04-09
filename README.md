<p align="center"><img src="https://i.ibb.co/gPPyM1X/Logo-Lite-crop.png" width="256px" border="0"></p>

<h1 align="center"> 🚀 Empathy Litengine</h1>

[![Latest Stable Version](https://poser.pugx.org/empathy-php/litengine/v)](//packagist.org/packages/empathy-php/litengine) [![Total Downloads](https://poser.pugx.org/empathy-php/litengine/downloads)](//packagist.org/packages/empathy-php/litengine) [![License](https://poser.pugx.org/empathy-php/litengine/license)](//packagist.org/packages/empathy-php/litengine)

**Empathy Litengine** - облегчённая версия фреймворка [Empathy Engine](https://github.com/empathy-framework/engine)

## Установка

```
composer require empathy-php/litengine
```

Используется совместно с **Empathy Core** или **Empathy Litecore**

Для лучшей работы рекомендуется прописать следующий код в корневом файле `composer.json`:

```json
{
    "scripts": {
        "empathy-run": "vendor/empathy-php/litecore/empathy.exe vendor/empathy-php/core/script.php"
    }
}
```

После чего можно будет исполнять код

```
composer empathy-run
```

для запуска проекта

Код приложения можно писать в файле `app.php` в корневой директории проекта

## Пример работы:

app.php
```php
<?php

require 'vendor/autoload.php';

use function Empathy\Engine\dn;

$form   = dn ('System.Windows.Forms.Form');
$button = dn ('System.Windows.Forms.Button');

$form->text = 'Example app';

$button->parent = $form;

$button->left   = 16;
$button->top    = 16;
$button->width  = 96;
$button->height = 32;

$button->text = 'Click me!';

$button->on ('click', function ()
{
    dn ('System.Windows.Forms.MessageBox')->show ('Hello, World!');
});

$form->showDialog ();
```

Авторы: [Подвирный Никита](https://vk.com/technomindlp) и [Кусов Андрей](https://vk.com/postmessagea)
