<?php
// Исходное уравнение. Можешь менять его на 'X - 12 = 35', '47 - X = 12', 'X + 5 = 10' и т.д.
$equation = 'X + 3 = 7';

// 1. Очищаем строку от пробелов для удобства разбора
$cleanEquation = str_replace(' ', '', $equation);

// 2. Разбиваем уравнение на левую часть и правую (после знака '=')
$parts = explode('=', $cleanEquation);
$leftPart = $parts[0];  // Например: '47-X'
$result = (float)$parts[1]; // Правая часть (всегда число): 12

// 3. Определяем оператор в левой части
$operator = '';
if (strpos($leftPart, '+') !== false) $operator = '+';
elseif (strpos($leftPart, '-') !== false) $operator = '-';
elseif (strpos($leftPart, '*') !== false) $operator = '*';
elseif (strpos($leftPart, '/') !== false) $operator = '/';

// 4. Разделяем левую часть по найденному оператору, чтобы найти число и положение X
$subParts = explode($operator, $leftPart);

// Проверяем, с какой стороны от оператора стоит X
if (strtoupper($subParts[0]) === 'X') {
    $position = 'X находится СЛЕВА от оператора';
    $number = (float)$subParts[1];
    
    // Вычисляем X в зависимости от оператора (X оператор Число = Результат)
    switch ($operator) {
        case '+': $x = $result - $number; $steps = "X = $result - $number"; break;
        case '-': $x = $result + $number; $steps = "X = $result + $number"; break;
        case '*': $x = $result / $number; $steps = "X = $result / $number"; break;
        case '/': $x = $result * $number; $steps = "X = $result * $number"; break;
    }
} else {
    $position = 'X находится СПРАВА от оператора';
    $number = (float)$subParts[0];
    
    // Вычисляем X в зависимости от оператора (Число оператор X = Результат)
    switch ($operator) {
        case '+': $x = $result - $number; $steps = "X = $result - $number"; break;
        case '-': $x = $number - $result; $steps = "X = $number - $result"; break;
        case '*': $x = $result / $number; $steps = "X = $result / $number"; break;
        case '/': $x = $number / $result; $steps = "X = $number / $result"; break;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solve the equation</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <header class="header">
        <img class="logo" src="logo.png" alt="logo">   
        <div class="title">Домашняя работа: Solve the equation</div>
        <img class="logo" src="logo.png" alt="logo">
    </header>

    <main class="main">
        <section class="card">
            <h1>Решение уравнения</h1>

            <div class="equation" style="font-size: 24px; font-weight: bold; margin: 20px 0; color: #007bff;">
                <?= htmlspecialchars($equation) ?>
            </div>

            <div class="result">
                <p>
                    <strong>Оператор:</strong> 
                    <span style="font-size: 18px; color: #ff0000;"><?= htmlspecialchars($operator) ?></span>
                </p>

                <p>
                    <strong>Расположение неизвестной переменной:</strong> 
                    <?= htmlspecialchars($position) ?>
                </p>

                <p>
                    <strong>Ход решения:</strong> 
                    <code><?= htmlspecialchars($steps) ?></code>
                </p>

                <p class="answer" style="font-size: 20px; font-weight: bold; color: green;">
                    Ответ: X = <?= $x ?>
                </p>
            </div>
        </section>
    </main>

    <footer class="footer">
        задание для самостоятельной работы
    </footer>
</body>
</html>
