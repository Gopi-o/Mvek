# import datetime

students = {
    "Анна": [5, 4, 5, 3, 5],
    "Борис": [3, 3, 4, 3, 4],
    "Виктор": [5, 5, 5, 5, 5],
    "Галина": [4, 4, 3, 4, 4],
}

best_student = None
best_average = 0
total_sum = 0
total_count = 0
has_triple = False

for student in students:
    student_sum = 0
    for grade in students[student]:
        student_sum = student_sum + grade
        total_sum = total_sum + grade
        total_count = total_count + 1
        if grade == 3:
            has_triple = True

    student_average = student_sum / len(students[student])
    print(student, "— средний балл:", student_average)

    if student_average > best_average:
        best_average = student_average
        best_student = student

group_average = total_sum / total_count
print("Средний балл по группе:", group_average)

if best_student is None:
    best_student = "нет данных"

if has_triple is True:
    print("В группе есть тройки")

print("Лучший студент:", best_student, "со средним баллом", best_average)
print("Всего оценок в журнале:", total_count)
