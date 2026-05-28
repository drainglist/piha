<?php
/**
 * Сложение двух чисел
 * @param float $a Первое число
 * @param float $b Второе число
 * @return float Сумма чисел
 */
function addNumbers(float $a, float $b): float
{
    return $a + $b;
}

/**
 * Вычитание двух чисел
 * @param float $a Уменьшаемое
 * @param float $b Вычитаемое
 * @return float Разность чисел
 */
function subtractNumbers(float $a, float $b): float
{
    return $a - $b;
}

/**
 * Умножение двух чисел
 * @param float $a Первый множитель
 * @param float $b Второй множитель
 * @return float Произведение чисел
 */
function multiplyNumbers(float $a, float $b): float
{
    return $a * $b;
}

/**
 * Деление двух чисел
 * @param float $a Делимое
 * @param float $b Делитель
 * @return float Частное чисел
 * @throws Exception При делении на ноль
 */
function divideNumbers(float $a, float $b): float
{
    // Проверка деления на ноль с учётом погрешности чисел с плавающей точкой
    if (abs($b) < 0.0000001) {
        throw new Exception('Деление на ноль невозможно');
    }

    return $a / $b;
}

/**
 * Возведение в степень
 * @param float $a Основание
 * @param float $b Показатель степени
 * @return float Результат возведения в степень
 * @throws Exception При некорректном результате (бесконечность или не число)
 */
function powerNumbers(float $a, float $b): float
{
    $result = $a ** $b;

    // Проверка, что результат - конечное число (не бесконечность и не NaN)
    if (!is_finite($result)) {
        throw new Exception('Некорректное возведение в степень');
    }

    return $result;
}

/**
 * Вычисление квадратного корня
 * @param float $number Число
 * @return float Квадратный корень
 * @throws Exception При попытке извлечь корень из отрицательного числа
 */
function squareRootNumber(float $number): float
{
    if ($number < 0) {
        throw new Exception('Корень из отрицательного числа невозможен');
    }

    return sqrt($number);
}

/**
 * Натуральный логарифм (ln)
 * @param float $number Число
 * @return float Натуральный логарифм
 * @throws Exception Для неположительных чисел
 */
function naturalLogNumber(float $number): float
{
    if ($number <= 0) {
        throw new Exception('ln можно вычислить только для положительного числа');
    }

    return log($number);
}

/**
 * Десятичный логарифм (log10)
 * @param float $number Число
 * @return float Десятичный логарифм
 * @throws Exception Для неположительных чисел
 */
function decimalLogNumber(float $number): float
{
    if ($number <= 0) {
        throw new Exception('log можно вычислить только для положительного числа');
    }

    return log10($number);
}

/**
 * Вычисление факториала
 * @param float $number Число (должно быть целым неотрицательным)
 * @return float Факториал числа
 * @throws Exception При отрицательном числе, нецелом числе или слишком большом числе
 */
function factorialNumber(float $number): float
{
    // Факториал определён только для неотрицательных чисел
    if ($number < 0) {
        throw new Exception('Факториал отрицательного числа невозможен');
    }

    // Проверка, что число целое (с учётом погрешности)
    if (abs($number - round($number)) > 0.0000001) {
        throw new Exception('Факториал можно вычислить только для целого числа');
    }

    // Ограничение, так как факториал быстро растёт и вызывает переполнение
    if ($number > 170) {
        throw new Exception('Слишком большое число для факториала');
    }

    // Вычисление факториала через цикл
    $result = 1;
    for ($i = 2; $i <= (int)$number; $i++) {
        $result *= $i;
    }

    return $result;
}

/**
 * Форматирование числа для отображения
 * Убирает лишние нули после запятой и десятичную точку, если число целое
 * @param float $number Число для форматирования
 * @return string Отформатированное число
 */
function formatResult(float $number): string
{
    // Если число целое (с учётом погрешности), возвращаем его как целое
    if (abs($number - round($number)) < 0.0000001) {
        return (string)round($number);
    }

    // Иначе обрезаем лишние нули и точку в конце
    return rtrim(rtrim(number_format($number, 10, '.', ''), '0'), '.');
}

/**
 * Класс для разбора и вычисления математических выражений
 * Поддерживает: +, -, *, /, ^, !, sqrt, ln, log, pi, e, скобки, унарные операторы
 */
class CalculatorParser
{
    private string $expression = '';  // Исходное выражение
    private int $position = 0;        // Текущая позиция при разборе

    /**
     * Основной метод: вычисляет переданное выражение
     * @param string $expression Математическое выражение
     * @return float Результат вычисления
     * @throws Exception При ошибках в выражении
     */
    public function calculate(string $expression): float
    {
        // Подготовка выражения: удаление пробелов, замена запятых на точки, приведение к нижнему регистру
        $this->expression = strtolower(trim($expression));
        $this->expression = str_replace(',', '.', $this->expression);
        $this->expression = preg_replace('/\s+/', '', $this->expression);
        $this->position = 0;

        // Проверка на пустое выражение
        if ($this->expression === '') {
            throw new Exception('Введите выражение');
        }

        // Проверка на допустимые символы
        if (!preg_match('/^[0-9+\-*\/^().!a-z]+$/', $this->expression)) {
            throw new Exception('Выражение содержит недопустимые символы');
        }

        // Запуск разбора с наивысшего приоритета (сложение/вычитание)
        $result = $this->parseExpression();

        // Проверка, что всё выражение разобрано до конца
        if ($this->position !== strlen($this->expression)) {
            throw new Exception('Некорректное выражение');
        }

        // Проверка, что результат - конечное число
        if (!is_finite($result)) {
            throw new Exception('Результат невозможно вычислить');
        }

        return $result;
    }

    /**
     * Разбор сложения и вычитания (самый низкий приоритет)
     * @return float Результат
     */
    private function parseExpression(): float
    {
        $result = $this->parseTerm();

        while (!$this->isEnd()) {
            if ($this->match('+')) {
                $result = addNumbers($result, $this->parseTerm());
            } elseif ($this->match('-')) {
                $result = subtractNumbers($result, $this->parseTerm());
            } else {
                break;
            }
        }

        return $result;
    }

    /**
     * Разбор умножения и деления (средний приоритет)
     * @return float Результат
     */
    private function parseTerm(): float
    {
        $result = $this->parsePower();

        while (!$this->isEnd()) {
            if ($this->match('*')) {
                $result = multiplyNumbers($result, $this->parsePower());
            } elseif ($this->match('/')) {
                $result = divideNumbers($result, $this->parsePower());
            } else {
                break;
            }
        }

        return $result;
    }

    /**
     * Разбор возведения в степень (высокий приоритет, правоассоциативно)
     * @return float Результат
     */
    private function parsePower(): float
    {
        $result = $this->parseUnary();

        if ($this->match('^')) {
            $result = powerNumbers($result, $this->parsePower());
        }

        return $result;
    }

    /**
     * Разбор унарных операторов (+ и -)
     * @return float Результат
     */
    private function parseUnary(): float
    {
        // Унарный плюс ничего не меняет
        if ($this->match('+')) {
            return $this->parseUnary();
        }

        // Унарный минус меняет знак
        if ($this->match('-')) {
            return -$this->parseUnary();
        }

        return $this->parsePostfix();
    }

    /**
     * Разбор постфиксных операторов (факториал)
     * @return float Результат
     */
    private function parsePostfix(): float
    {
        $result = $this->parsePrimary();

        // Факториал может быть применён несколько раз подряд
        while ($this->match('!')) {
            $result = factorialNumber($result);
        }

        return $result;
    }

    /**
     * Разбор первичных конструкций: числа, скобки, функции, константы
     * @return float Результат
     * @throws Exception При синтаксических ошибках
     */
    private function parsePrimary(): float
    {
        // Выражение в скобках
        if ($this->match('(')) {
            $result = $this->parseExpression();
            if (!$this->match(')')) {
                throw new Exception('Не закрыта скобка');
            }
            return $result;
        }

        // Функция sqrt
        if ($this->startsWith('sqrt')) {
            return $this->parseFunction('sqrt');
        }

        // Функция ln (натуральный логарифм)
        if ($this->startsWith('ln')) {
            return $this->parseFunction('ln');
        }

        // Функция log (десятичный логарифм)
        if ($this->startsWith('log')) {
            return $this->parseFunction('log');
        }

        // Константа π (пи)
        if ($this->startsWith('pi')) {
            $this->position += 2;
            return pi();
        }

        // Константа e (число Эйлера)
        if ($this->startsWith('e')) {
            $this->position += 1;
            return exp(1);
        }

        // Обычное число
        return $this->parseNumber();
    }

    /**
     * Разбор математических функций (sqrt, ln, log)
     * @param string $functionName Имя функции
     * @return float Результат функции
     * @throws Exception При ошибках синтаксиса или вычисления
     */
    private function parseFunction(string $functionName): float
    {
        // Пропускаем имя функции
        $this->position += strlen($functionName);

        // После имени должна быть открывающая скобка
        if (!$this->match('(')) {
            throw new Exception('После функции должна быть открывающая скобка');
        }

        // Разбираем аргумент функции
        $value = $this->parseExpression();

        // Проверяем закрывающую скобку
        if (!$this->match(')')) {
            throw new Exception('После аргумента функции должна быть закрывающая скобка');
        }

        // Вызываем соответствующую математическую функцию
        if ($functionName === 'sqrt') {
            return squareRootNumber($value);
        }
        if ($functionName === 'ln') {
            return naturalLogNumber($value);
        }
        if ($functionName === 'log') {
            return decimalLogNumber($value);
        }

        throw new Exception('Неизвестная функция');
    }

    /**
     * Разбор числа (целого или с десятичной точкой)
     * @return float Число
     * @throws Exception Если число не найдено
     */
    private function parseNumber(): float
    {
        $number = '';
        $hasDigit = false;   // Был ли хотя бы один цифровой символ
        $hasDot = false;     // Была ли десятичная точка

        // Собираем символы, пока это цифры или одна точка
        while (!$this->isEnd()) {
            $char = $this->current();

            if (ctype_digit($char)) {
                $number .= $char;
                $hasDigit = true;
                $this->position++;
                continue;
            }

            if ($char === '.' && !$hasDot) {
                $number .= $char;
                $hasDot = true;
                $this->position++;
                continue;
            }

            break;
        }

        // Если не нашли ни одной цифры - ошибка
        if (!$hasDigit) {
            throw new Exception('Ожидалось число');
        }

        return (float)$number;
    }

    /**
     * Проверяет, соответствует ли текущий символ ожидаемому, и продвигает позицию
     * @param string $expected Ожидаемый символ
     * @return bool true, если символ совпал
     */
    private function match(string $expected): bool
    {
        if ($this->current() === $expected) {
            $this->position++;
            return true;
        }
        return false;
    }

    /**
     * Проверяет, начинается ли остаток строки с указанной подстроки
     * @param string $value Подстрока для проверки
     * @return bool true, если совпадает
     */
    private function startsWith(string $value): bool
    {
        return substr($this->expression, $this->position, strlen($value)) === $value;
    }

    /**
     * Возвращает текущий символ или null, если достигнут конец строки
     * @return string|null Текущий символ
     */
    private function current(): ?string
    {
        if ($this->isEnd()) {
            return null;
        }
        return $this->expression[$this->position];
    }

    /**
     * Проверяет, достигнут ли конец строки
     * @return bool true, если позиция за пределами строки
     */
    private function isEnd(): bool
    {
        return $this->position >= strlen($this->expression);
    }
}

// ==================== ОБРАБОТКА HTTP-ЗАПРОСОВ ====================

// Обработка POST-запроса (отправка формы)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expression = $_POST['expression'] ?? '';

    try {
        $parser = new CalculatorParser();
        $result = $parser->calculate($expression);

        // Успешное вычисление: перенаправляем с результатом
        header(
            'Location: ./index.php?expression=' . urlencode($expression) .
            '&result=' . urlencode(formatResult($result))
        );
        exit;
    } catch (Exception $error) {
        // Ошибка: перенаправляем с сообщением об ошибке
        header(
            'Location: ./index.php?expression=' . urlencode($expression) .
            '&error=' . urlencode($error->getMessage())
        );
        exit;
    }
}

// Получение данных из GET-параметров (после перенаправления)
$expressionFromGet = $_GET['expression'] ?? '';
$resultFromGet = $_GET['result'] ?? '';
$errorFromGet = $_GET['error'] ?? '';

// Отображаем результат, если он есть, иначе введённое выражение
$displayValue = $resultFromGet !== '' ? $resultFromGet : $expressionFromGet;
?>

<!-- ==================== HTML-РАЗМЕТКА КАЛЬКУЛЯТОРА ==================== -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculator</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <header class="header">
        <img class="logo" src="/logo.png" alt="logo">
        <div class="title">Домашняя работа: Calculator</div>
        <div class="header-spacer"></div>
    </header>

    <main class="main">
        <section class="calculator">
            <h1>Калькулятор</h1>

            <!-- Форма калькулятора, отправляет данные методом POST -->
            <form action="./index.php" method="POST" class="calculator-form" id="calculatorForm">
                <!-- Поле ввода/отображения результата -->
                <input
                    class="display"
                    id="display"
                    type="text"
                    name="expression"
                    value="<?= htmlspecialchars($displayValue) ?>"
                    placeholder="Введите выражение"
                    autocomplete="off"
                >

                <!-- Отображение ошибки, если она есть -->
                <?php if ($errorFromGet !== ''): ?>
                    <p class="error">
                        <?= htmlspecialchars($errorFromGet) ?>
                    </p>
                <?php endif; ?>

                <!-- Кнопки калькулятора -->
                <div class="buttons">
                    <!-- Управляющие кнопки -->
                    <button type="button" id="clear">C</button>
                    <button type="button" id="backspace">←</button>
                    <button type="button" data-value="(">(</button>
                    <button type="button" data-value=")">)</button>

                    <!-- Математические функции -->
                    <button type="button" data-value="sqrt(">√</button>
                    <button type="button" data-value="ln(">ln</button>
                    <button type="button" data-value="log(">log</button>
                    <button type="button" data-value="!">!</button>

                    <!-- Константы и операторы -->
                    <button type="button" data-value="pi">π</button>
                    <button type="button" data-value="e">e</button>
                    <button type="button" data-value="^">^</button>
                    <button type="button" data-value="/">/</button>

                    <!-- Цифры и базовые операторы -->
                    <button type="button" data-value="7">7</button>
                    <button type="button" data-value="8">8</button>
                    <button type="button" data-value="9">9</button>
                    <button type="button" data-value="*">*</button>

                    <button type="button" data-value="4">4</button>
                    <button type="button" data-value="5">5</button>
                    <button type="button" data-value="6">6</button>
                    <button type="button" data-value="-">-</button>

                    <button type="button" data-value="1">1</button>
                    <button type="button" data-value="2">2</button>
                    <button type="button" data-value="3">3</button>
                    <button type="button" data-value="+">+</button>

                    <button type="button" data-value="0">0</button>
                    <button type="button" data-value=".">.</button>
                    <!-- Кнопка отправки формы (вычисление) -->
                    <button type="submit" class="equals">=</button>
                </div>
            </form>

            <!-- Подсказка с примерами использования -->
            <div class="help">
                <p>Примеры: <code>sqrt(9)</code>, <code>2^3</code>, <code>5!</code>, <code>ln(e)</code>, <code>log(100)</code>, <code>-(2+3)</code></p>
            </div>
        </section>
    </main>

    <footer class="footer">
        задание для самостоятельно работы
    </footer>

    <!-- Подключаем JavaScript для интерактивности кнопок -->
    <script src="./script.js"></script>
</body>
</html>