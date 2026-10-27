import logging
import sys
import time
import requests

logger = logging.getLogger("IntegrationTestLogger")
logger.setLevel(logging.INFO)

formatter = logging.Formatter(
    fmt="%(asctime)s - %(levelname)s - %(message)s",
    datefmt="%Y-%m-%d %H:%M:%S"
)

console_handler = logging.StreamHandler(sys.stdout)
console_handler.setFormatter(formatter)
logger.addHandler(console_handler)


def run_integration_test(url_override=None):

    base_url = url_override if url_override else "https://jsonplaceholder.typicode.com/posts"
    
    payload = {
        "title": "Мой тестовый заказ",
        "body": "Описание заказа",
        "userId": 1
    }
    
    logger.info("СТАРТ ИНТЕГРАЦИОННОГО ТЕСТА")
    
    try:
        logger.info(f"Отправка POST-запроса на создание заказа: {base_url}")
        
        response_create = requests.post(base_url, json=payload, timeout=5)
        
        response_create.raise_for_status()
        
        data = response_create.json()
        
        post_id = data["id"] # !
        logger.info(f"Заказ успешно создан. Получен ID: {post_id}")
        
        
        get_url = f"{base_url}/{post_id}" if not url_override else f"{base_url}sss/{post_id}"
        logger.info(f"Отправка GET-запроса на получение заказа: {get_url}")
        
        response_get = requests.get(get_url, timeout=5)
        response_get.raise_for_status()
        
        get_data = response_get.json()
        logger.info(f"Заказ успешно получен! Название в базе: '{get_data.get('title')}'")
        logger.info("--- ТЕСТ УСПЕШНО ЗАВЕРШЕН (PASSED) ---")
        return True

    except requests.exceptions.Timeout as e:
        logger.error(f"ОШИБКА ТАЙМАУТА: Сервер слишком долго не отвечал (более 5 сек). Детали: {e}")
        return False
        
    except requests.exceptions.RequestException as e:
        logger.error(f"ОШИБКА СЕТИ: Не удалось связаться с сервером или получен плохой HTTP-статус. Детали: {e}")
        return False
        
    except KeyError as e:
        logger.error(f"ОШИБКА СТРУКТУРЫ JSON: В ответе сервера отсутствует ожидаемый ключ {e}")
        return False
        
    except ValueError as e:
        logger.error(f"ОШИБКА ВАЛИДАЦИИ: Ответ сервера не является валидным JSON. Детали: {e}")
        return False
        
    except Exception as e:
        logger.error(f"НЕПРЕДВИДЕННАЯ ОШИБКА ТЕСТА: {e}")
        return False


if __name__ == "__main__":
    print("\nСЦЕНАРИЙ 1: УСПЕШНЫЙ ТЕСТ")
    run_integration_test()
    
    print("\nСЦЕНАРИЙ 2: ИСКУССТВЕННЫЙ СБОЙ (Неверный URL)")
    broken_url = "https://jsonplaceholder.typicode.com/postsss"
    run_integration_test(url_override=broken_url)
