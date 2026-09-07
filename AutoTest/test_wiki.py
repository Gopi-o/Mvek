import os
from datetime import datetime
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

os.makedirs("data/screenshots", exist_ok=True)
search_Text = "Россия"


driver = webdriver.Edge()
driver.get("https://www.wikipedia.org")
# driver.get("https://ru.wikipedia.org")

search = driver.find_element(By.ID, "searchInput")
search.send_keys(search_Text)
search.send_keys(Keys.ENTER)

wait =WebDriverWait(driver, 10)
heading = wait.until(EC.presence_of_element_located((By.ID, 'firstHeading')))

# heading = driver.find_element(By.ID, "firstHeading")
assert search_Text in heading.text
timestamp = datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
heading_path = os.path.join("data", "screenshots", f"{search_Text}_{timestamp}.png")
driver.save_screenshot(heading_path)
print("Тест прошел успешно!")
print(f"Скриншот заголовка: {heading_path}")
driver.quit()
