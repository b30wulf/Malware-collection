from PIL import Image

import zlib
import os


def save_image(screen_info, client_id, storage):
    date = screen_info['date'].split('_')[0]
    dir = check_client_storage(storage, client_id, date)
    image = Image.frombuffer('RGB', (screen_info['width'], screen_info['height']), zlib.decompress(screen_info['screen_bits']), 'raw', 'BGRX', 0, 1)
    screen_bits = image.convert('RGBA')
    screen_path = os.path.join(dir, '%s.png' % screen_info['date'])
    screen_bits.save(screen_path, 'png')
    return screen_path, screen_info['date'], screen_info['title_name'], date

def check_client_storage(storage, client_id, date):
    screenshot_path = os.path.join(storage, client_id, 'Screenshots', date)
    if not os.path.exists(screenshot_path):
        os.makedirs(screenshot_path)
    return screenshot_path