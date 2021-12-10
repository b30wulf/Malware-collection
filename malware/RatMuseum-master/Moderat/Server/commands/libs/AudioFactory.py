import datetime
import pyaudio
import wave
import zlib
import os

def get_date_time():
    now = datetime.datetime.now()
    year = now.year
    month = now.month
    if len(str(month)) < 2:
        month = '0'+str(month)
    day = now.day
    if len(str(day)) < 2:
        day = '0'+str(day)
    hour = now.hour
    minute = now.minute
    second = now.second
    return '%s-%s-%s_%s-%s-%s' % (year, month, day, hour, minute, second)


def wav_generator(client_id, audio_info, storage):
    date = get_date_time()
    dir = check_client_storage(storage, client_id, date.split('_')[0])
    audio = pyaudio.PyAudio()
    wav_file_path = os.path.join(dir, '%s.wav') % date
    waveFile = wave.open(wav_file_path, 'wb')
    waveFile.setnchannels(audio_info['channel'])
    waveFile.setsampwidth(audio.get_sample_size(audio_info['format']))
    waveFile.setframerate(audio_info['rate'])
    waveFile.writeframes(zlib.decompress(audio_info['raw']))
    waveFile.close()
    return wav_file_path, date


def check_client_storage(storage, client_id, date):
    audio_path = os.path.join(storage, client_id, 'Audio', date)
    if not os.path.exists(audio_path):
        os.makedirs(audio_path)
    return audio_path