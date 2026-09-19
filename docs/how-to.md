# Как использовать Lathe

После успешной установки и настройки Lathe (см. Быстрый старт - ссылка) вы можете использовать его конструкции в своих шаблонах. 

## Синтаксис шаблонов
Шаблоны Lathe после компиляции это буквально строки HEREDOC в которые встраиваются анонимные функции через интерполяцию. Следовательно и весь синтаксис это нативный PHP синтаксис работы с 
интерполяцией. Например:
```html
<h1>{$pl($pageTitle)}</h1>
<p>{$description}</p>
```

## Все доступные методы

### $loop — цикл
Циклы используются для перебора массивов и генерации повторяющихся элементов.

**Сигнатура:** `loop(iterable $in, ?Closure $callback = null): string`
```html
<ul>
    {$loop($users, fn($user) => "<li>{$user->name}</li>")}
</ul>
```

### $if — условие
Условный вывод результата в шаблон.

**Сигнатура:** `if(mixed $flag, string|Closure $template, string|Closure|null $elseTemplate = null)`
```html
<div>
    {$if($user->active, 'Активен', 'Неактивен')}
</div>
```

### $pl — вывод
Выводит значение или результат замыкания. Полезно, когда нужно выполнить PHP-код и вывести результат.

**Сигнатура:** `pl(string|Closure $template): string`
```html
<p>Текущее время: {$pl(fn() => date('H:i:s'))}</p>
<p>Простое значение: {$pl('Hello')}</p>
```

### $mr — объединение
Объединяет несколько значений в одну строку.

**Сигнатура:** `mr(...$vals): string`
```html
<div class="{$mr('user', 'card', $user->role)}">
    ...
</div>
```
Результат:
```html
<div class="user card admin">
    ...
</div>
```

### $count — подсчёт
Выводит количество элементов в Countable сущностях.

**Сигнатура:** `count(array|Countable $input): int`
```html
<p>Всего пользователей: {$count($users)}</p>
```

## Альтернативное использование
Чтобы избежать процесса компиляции шаблонов в PHP-код, может быть использован альтернативный подход для работы с разметкой как со строками. 
Этот принцип предполагает вызов методов шаблонизации через экземпляр класса `KolodochkaDev\Lathe\StringTemplator` или подключение трейта `KolodochkaDev\Lathe\Traits\HasTemplator`.

**StringTemplator:**
```php
<?php

use KolodochkaDev\Lathe\StringTemplator;

class Button
{
    public function __invoke(string $title, string $type = ''): string
    {
        $t = new StringTemplator;
        return <<<HTML
            <button class="btn {$t->if($type, "btn-$type")}">
                {$t->pl($title)}
            </button>
        HTML;
    }
}
```

**HasTemplator:**
```php
<?php

use KolodochkaDev\Lathe\Traits\HasTemplator;

class Button
{
    use HasTemplator;

    public function __invoke(string $title, string $type = ''): string
    {
        return <<<HTML
            <button class="btn {$this->if($type, "btn-$type")}">
                {$this->pl($title)}
            </button>
        HTML;
    }
}
```

### Вызов:
```php
$button = new Button;
echo $button('Отправить', 'success');
// <button class="btn btn-success">Отправить</button>
```


