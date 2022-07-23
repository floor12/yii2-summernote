# yii2-summernote

Виджет WYSIWYG-редактора [Summernote](https://summernote.org/ ) для фреймворка [Yii2](https://yiiframework.ru/), c включенными ассетами и поправлеными багами в js-библетеках.

## Установка

Устанавливаем модуль через composer:
Выполняем команду

```bash
$ composer require floor12/yii2-summernote
```

## Инструкция

Простейший пример:

```php
use floor12\summernote\Summernote;

echo Summernote::widget(['name' => 'some_field'])
```

Пример работы с `ActiveForm` и `ActiveRecord` моделью:

```php
$form = ActiveForm::begin();

echo $form->field($model, 'content_ru')
    ->widget(Summernote::class);
             
ActiveForm::end();
```

Пример интеграции с [модулем файлов](https://github.com/floor12/yii2-module-files), чтобы перехватывать загружаемые в
текст изображения, сохранять их отдельно и затем
использовать.

```php
$form = ActiveForm::begin();

echo $form->field($model, 'content_ru')
    ->widget(Summernote::class, [
        'fileField' => 'imagesDesktop',
        'fileModelClass' => $model::class
    ]);

echo $form->field($model, 'imagesDesktop')
    ->widget(FileInputWidget::class);

ActiveForm::end();
```