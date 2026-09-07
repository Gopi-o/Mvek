import os
import pytest
from datetime import datetime
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.support import expected_conditions as EC

os.makedirs("data/screenshots", exist_ok=True)

@pytest.fixture
def driver():
    drv = webdriver.Edge()
    drv.implicitly_wait(5)
    yield drv
    drv.quit()



def test_dragdrop(driver):
    driver.get("https://the-internet.herokuapp.com/drag_and_drop")

    column_a = driver.find_element(By.ID, "column-a")
    column_b = driver.find_element(By.ID, "column-b")

    assert "A" in column_a.text
    print(f"before: A = '{column_a.text}', B = '{column_b.text}")
    timestamp = datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
    heading_path = os.path.join("data", "screenshots", f"До_{timestamp}.png")
    driver.save_screenshot(heading_path)

    ActionChains(driver).drag_and_drop(column_a, column_b).perform()

    column_a_after = driver.find_element(By.ID, "column-a")
    assert "B" in column_a.text
    print(f"after: A = '{column_a_after.text}, B = '{column_b.text}")
    timestamp = datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
    heading_path = os.path.join("data", "screenshots", f"После{timestamp}.png")
    driver.save_screenshot(heading_path)







