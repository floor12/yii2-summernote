# yii2-summernote

The [Summernote](https://summernote.org/) WYSIWYG-editor [Yii-widget](https://yiiframework.ru/), with all included assets.

![Widget example](https://floor12.net/en/files/default/image?hash=81ef4ae8ce4cf1c288ad9dd78ff72ec2&width=1500)
## Installation

Install the widget via composer:
Execute the command

```bash
$ composer require floor12/yii2-summernote
```

## Instruction

The simplest example:

```php
use floor12\summernote\Summernote;

echo Summernote::widget(['name' => 'some_field'])
```


The`ActiveForm` and `ActiveRecord` model example:

```php
$form = ActiveForm::begin();

echo $form->field($model, 'content_ru')
    ->widget(Summernote::class);
             
ActiveForm::end();
```


An example of integrating with [my files module](https://github.com/floor12/yii2-module-files) to intercept editors uploads, save them separately and then use in the editor.

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

![Working example](https://floor12.net/en/files/default/image?hash=868c9752a86820692dabcb334f766df7&width=1500)