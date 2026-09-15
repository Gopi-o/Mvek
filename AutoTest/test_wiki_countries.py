import os
import pytest
import allure
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
    with allure.step("open Edge WebDriver"):
        drv = webdriver.Edge()
    drv.implicitly_wait(5)
    yield drv
    with allure.step("quit Edge WebDriver"):
        drv.quit()

@pytest.mark.parametrize("country", [
    "Россия", "Япония", "Франция", "Германия", "Италия", "Китай", "Бразилия", "Канада", "Индия", "Египет"
])

@allure.feature("Wikipedia Search")
@allure.story("Search for countries and verify heading")
@allure.title("Test Wikipedia search for {country}")
def test_wiki_country(driver, country):
    with allure.step(f"Open Wikipedia and search for {country}"):
        driver.get("https://www.wikipedia.org")
        # driver.get("https://ru.wikipedia.org")

    with allure.step(f"Enter search text"):
        search = driver.find_element(By.ID, "searchInput")
        search.send_keys(country)

    with allure.step(f"Submit search's form"):
        search.send_keys(Keys.ENTER)


    with allure.step(f"Wait for heading to be present content"):
        wait = WebDriverWait(driver, 10)
        wait.until(EC.text_to_be_present_in_element((By.ID, "firstHeading"), country))

    with allure.step(f"Verify heading text"):
        heading = driver.find_element(By.ID, "firstHeading")
        assert country in heading.text

    with allure.step(f"Take screenshot of heading"):
        timestamp = datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
        heading_path = os.path.join("data", "screenshots", f"{country}_{timestamp}.png")
        driver.save_screenshot(heading_path)
        allure.attach.file(heading_path, name="Screenshot_{country}", attachment_type=allure.attachment_type.PNG)
    print("Тест прошел успешно!")
    print(f"Скриншот заголовка: {heading_path}")





