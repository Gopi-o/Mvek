import os
import pytest
from time import time
from datetime import datetime
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC


class WikipediaPage:
    SEARCH_INPUT = (By.ID, "searchInput")
    FIRST_HEADING = (By.ID, "firstHeading")
    
    def __init__(self, driver):
        self.driver = driver
        self.wait = WebDriverWait(driver, 10)
    
    def open(self):
        self.driver.get("https://www.wikipedia.org")
        return self
    
    def search(self, text):
        search_field = self.driver.find_element(*self.SEARCH_INPUT)
        search_field.send_keys(text)
        search_field.send_keys(Keys.ENTER)
        return self
    
    def wait_for_heading(self, expected_text):
        self.wait.until(
            EC.text_to_be_present_in_element(self.FIRST_HEADING, expected_text)
        )
        return self
    
    def get_heading_text(self):
        heading = self.driver.find_element(*self.FIRST_HEADING)
        return heading.text
    
    def save_screenshot(self, search_text):
        os.makedirs("data/screenshots", exist_ok=True)
        timestamp = datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
        screenshot_path = os.path.join(
            "data", "screenshots", f"{search_text}_{timestamp}.png"
        )
        self.driver.save_screenshot(screenshot_path)
        return screenshot_path


@pytest.fixture
def driver():
    """Headless-драйвер для тестов."""
    options = webdriver.EdgeOptions()
    options.add_argument("--headless")
    options.add_argument("--disable-gpu")
    options.add_argument("--no-sandbox")
    
    drv = webdriver.Edge(options=options)
    drv.implicitly_wait(5)
    yield drv
    drv.quit()


@pytest.mark.parametrize("country", [
    "Россия", "Япония", "Франция", "Германия", "Италия",
    "Китай", "Бразилия", "Канада", "Индия", "Египет"
])
def test_wiki_country_pom_headless(driver, country):
    
    start = time()
    page = WikipediaPage(driver).open()
    load_time = time() - start
    
    print(f"\n {country}: загрузка {load_time:.2f} сек")
    assert load_time < 3.0, f"Медленная загрузка: {load_time:.2f} сек"
    
    page.search(country).wait_for_heading(country)
    heading_text = page.get_heading_text()
    
    assert country in heading_text, f"Ожидал '{country}', получил '{heading_text}'"
    
    screenshot_path = page.save_screenshot(country)
    print(f"{country} — OK | Скриншот: {screenshot_path}")
