import os
from pathlib import Path
import pytest
import tempfile
from datetime import datetime
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.support import expected_conditions as EC

os.makedirs("data/screenshots", exist_ok=True)
upload_file = Path(__file__).parent / "test_file_upload.py"

@pytest.fixture
def driver():
    drv = webdriver.Edge()
    drv.implicitly_wait(5)
    yield drv
    drv.quit()



def test_file_upload(driver):
    driver.get("https://the-internet.herokuapp.com/upload")

    file_input = driver.find_element(By.ID, "file-upload")
    file_input.send_keys(str(upload_file))

    driver.find_element(By.ID, "file-submit").click()

    wait = WebDriverWait(driver, 10)
    uploaded = wait.until(EC.presence_of_element_located((By.ID, "uploaded-files")))

    filename = os.path.basename(upload_file)
    assert filename in uploaded.text
    print(f"Файл '{filename}' успешно загружен!")
    timestamp = datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
    heading_path = os.path.join("data", "screenshots", f"{filename}_{timestamp}.png")
    driver.save_screenshot(heading_path)









