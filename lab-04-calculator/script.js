// Находим основные элементы калькулятора в DOM-дереве по их ID и атрибутам
const display = document.querySelector('#display'); // Экран/инпут калькулятора
const clearButton = document.querySelector('#clear'); // Кнопка полной очистки (C)
const backspaceButton = document.querySelector('#backspace'); // Кнопка удаления одного символа (<-)
const valueButtons = document.querySelectorAll('[data-value]'); // Все кнопки цифр и операций с атрибутом data-value

/**
 * Функция вставки символа в текущую позицию курсора (каретки)
 * @param {string} value - Значение, которое нужно вставить (цифра или оператор)
 */
function insertValue(value) {
    // Получаем индексы начала и конца выделения текста в инпуте
    const start = display.selectionStart;
    const end = display.selectionEnd;

    // Формируем новое значение экрана: текст ДО курсора + новый символ + текст ПОСЛЕ курсора
    display.value =
        display.value.slice(0, start) +
        value +
        display.value.slice(end);

    // Вычисляем новую позицию курсора сразу после вставленного символа
    const newPosition = start + value.length;

    // Возвращаем фокус на экран, чтобы пользователь мог продолжать ввод с клавиатуры
    display.focus();
    // Принудительно устанавливаем курсор в новую вычисленную позицию
    display.setSelectionRange(newPosition, newPosition);
}

// Перебираем все кнопки с цифрами/операциями и вешаем на них обработчик клика
valueButtons.forEach((button) => {
    button.addEventListener('click', () => {
        // При клике вызываем функцию вставки и передаем значение из data-атрибута кнопки
        insertValue(button.dataset.value);
    });
});

// Обработчик клика для кнопки полной очистки экрана
clearButton.addEventListener('click', () => {
    display.value = ''; // Стираем весь текст в инпуте
    display.focus();    // Возвращаем фокус на экран
});

// Обработчик клика для кнопки Backspace (удаление символа)
backspaceButton.addEventListener('click', () => {
    // Получаем текущее положение курсора или границы выделенного текста
    const start = display.selectionStart;
    const end = display.selectionEnd;

    // Сценарий 1: Если пользователь выделил мышкой часть текста (start не равен end)
    if (start !== end) {
        // Просто удаляем выделенный кусок текста
        display.value = display.value.slice(0, start) + display.value.slice(end);
        // Ставим курсор в точку, где начиналось выделение
        display.setSelectionRange(start, start);
    // Сценарий 2: Если выделения нет, но курсор стоит не в самом начале строки (есть что удалять)
    } else if (start > 0) {
        // Удаляем один символ строго слева от курсора (на один индекс назад от start)
        display.value = display.value.slice(0, start - 1) + display.value.slice(start);
        // Сдвигаем курсор на один символ назад
        display.setSelectionRange(start - 1, start - 1);
    }

    // Возвращаем фокус на экран калькулятора
    display.focus();
});

// Обработчик нажатия клавиш на физической клавиатуре внутри экрана
display.addEventListener('keydown', (event) => {
    // Если пользователь нажимает клавишу "Escape" (Esc)
    if (event.key === 'Escape') {
        display.value = ''; // Полностью очищаем экран калькулятора
    }
});
