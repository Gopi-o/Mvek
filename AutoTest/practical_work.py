"""
Практическая работа: unit-тесты на pytest.
Запуск: pytest practical_work.py -v
"""

import pytest



def add(a, b):
    """Сложение двух чисел."""
    return a + b


def is_even(n):
    """True, если число чётное."""
    return n % 2 == 0


def max_of_two(a, b):
    """Наибольшее из двух чисел."""
    return max(a, b)


def divide(a, b):
    """Деление. Если b == 0 — ошибка."""
    if b == 0:
        raise ValueError("Деление на ноль")
    return a / b


def test_add_example():
    assert add(2, 3) == 5


# ---------- ЗАДАНИЕ 1: add (2 теста) ----------


# add_positive
@pytest.mark.parametrize("a, b, expected", [
    (2, 3, 5),
    (-5, 3, -2),
    (0, 0, 0),
    (1.8, 1.2, 3),
])
def test_add_positive(a, b, expected):
    assert add(a, b) == pytest.approx(expected)



# add_negative
@pytest.mark.parametrize("a, b, expected", [
    (2, -3, -1),
    (-5, -3, -8),
    (0, 0, 0),
    (1.8, -1.2, 0.6),
])
def test_add_negative(a, b, expected):
    assert add(a, b) == pytest.approx(expected)


# ---------- ЗАДАНИЕ 2: is_even (2 теста) ----------


# is_even_true
@pytest.mark.parametrize("n, expected", [
    (4, True),
    (0, True),
    (-2, True),
])
def test_is_even_true(n, expected):
    assert is_even(n) is expected



# is_even_false
@pytest.mark.parametrize("n, expected", [
    (7, False),
    (-3, False)
])
def test_is_even_false(n, expected):
    assert is_even(n) is expected


# ---------- ЗАДАНИЕ 3: max_of_two (2 теста) ----------


# max_of_two
@pytest.mark.parametrize("a, b, expected", [
    (10, 5, 10),
    (5, 5, 5),
])
def test_max_first(a, b, expected):
    assert max_of_two(a, b) == expected

# max_of_two
@pytest.mark.parametrize("a, b, expected", [
    (1, 9, 9),
    (-1, -5, -1),
])
def test_max_second(a, b, expected):
    assert max_of_two(a, b) == expected


# ---------- ЗАДАНИЕ 4: divide (2 теста) ----------


# divide
@pytest.mark.parametrize("a, b, expected", [
    (10, 2, 5.0),
    (1, 3, 1/3),
    (-6, 2, -3.0),
])
def test_divide_simple(a, b, expected):
    assert divide(a, b) == pytest.approx(expected)


def test_divide_by_zero():
    with pytest.raises(ValueError, match="Деление на ноль"):
        divide(10, 0)



if __name__ == "__main__":
    pytest.main([__file__, "-v"])
