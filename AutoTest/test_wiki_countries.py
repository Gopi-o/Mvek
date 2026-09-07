import os
import pytest
from datetime import datetime
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

os.makedirs("data/screenshots", exist_ok=True)
search_Text = "Россия"

@pytest.fixture
def driver():
    drv = webdriver.Edge()
    drv.implicitly_wait(5)
    yield drv
    drv.quit()

@pytest.mark.parametrize("country", [
    "Россия", "Япония", "Франция", "Германия", "Италия", "Китай", "Бразилия", "Канада", "Индия", "Египет"
])

def test_wiki_country(driver, country):
    driver.get("https://www.wikipedia.org")
    # driver.get("https://ru.wikipedia.org")

    search = driver.find_element(By.ID, "searchInput")
    search.send_keys(country)
    search.send_keys(Keys.ENTER)

    wait = WebDriverWait(driver, 10)
    wait.until(EC.text_to_be_present_in_element((By.ID, "firstHeading"), country))

    heading = driver.find_element(By.ID, "firstHeading")
    assert country in heading.text
    timestamp = datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
    heading_path = os.path.join("data", "screenshots", f"{country}_{timestamp}.png")
    driver.save_screenshot(heading_path)
    print("Тест прошел успешно!")
    print(f"Скриншот заголовка: {heading_path}")





