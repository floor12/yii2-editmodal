# yii2-editmodal

[![Latest Stable Version](https://poser.pugx.org/floor12/yii2-editmodal/v/stable)](https://packagist.org/packages/floor12/yii2-editmodal)
[![Latest Unstable Version](https://poser.pugx.org/floor12/yii2-editmodal/v/unstable)](https://packagist.org/packages/floor12/yii2-editmodal)
[![License](https://poser.pugx.org/floor12/yii2-editmodal/license)](https://packagist.org/packages/floor12/yii2-editmodal)
[![Total Downloads](https://poser.pugx.org/floor12/yii2-editmodal/downloads)](https://packagist.org/packages/floor12/yii2-editmodal)

Это набор классов, позволяющих быстро организовать редактирование объектов в модальном окне Bootstrap`а.

В поставку входят (примеры использования будут ниже):
 - `EditModalAsset` - ассет с необходимыми js скриптами для работы компонента
 - `EditModalHelper` - класс-хелпер для быстрого рендеринга кнопок или формировани js функций управляющих модальным окном.
 - `EditModalAction` и `DeleteAction` - Action-класс, добавив в свой контроллер вы быстро получите редактировать и удалять объекты.
 - `ModalWindow` - класс для управления модальными окном, используемый для кастоматизации поведения модального окна.
 - `EdtiModalColumn` - наследник `yii\grid\Column` для быстрого рендеринга кнопок управления объектом с помощью модального окна внутри грида.

Установка пакета
------------
Выполняем команду
```bash
$ composer require floor12/yii2-editmodal
```

иди добавляем в секцию "requred" файла composer.json
```json
"floor12/yii2-editmodal": "~0.1.0"
```

