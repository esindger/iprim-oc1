# Iprim-oc1

Модуль  интеграции  магазина opencart v1.5.* в  [Iprim Market](https://market.iprim.ru) позволяет автоматически принимать заказы в ваш магазин.

### Требования
* PHP 5.3+
* Opencart 1.5.*
* [Vqmod](https://github.com/vqmod/vqmod)

### Установка
1. Установите Vqmod, если еще не установлено.  Последняя версия [тут](https://github.com/vqmod/vqmod/releases/tag/v2.5.1-opencart.zip). Инструкция [тут](https://github.com/vqmod/vqmod/wiki/Installing-vQmod-on-OpenCart).
2. Распакуйте архив `./dist.zip` в корневую директорию вашего магазина.
3. Зайдите в панель управления в раздел `Дополнения / Модули` и установите модуль `IPRIM.Маркет`.
4. Зайдите в настройки вашего магазина на сайте https://market.iprim.ru, перейдите на вкладку `Интеграция`. В поле `URL для передачи заказа` укажите `http://ВАШ_ДОМЕН_МАГАЗИНА/index.php?route=iprim/request`.
5. Зайдите в настройки модуля и укажите секретный ключ из настроек магазина на сайте https://market.iprim.ru
6. Готово.

