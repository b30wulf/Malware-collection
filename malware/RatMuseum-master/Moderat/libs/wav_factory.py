import matplotlib.pyplot as plt
import numpy as np
import wave
import contextlib
import os


def spectrum_analyzer_image(audio_path, audio_name, storage):
    spf = wave.open(audio_path, 'r')
    signal = spf.readframes(-1)
    signal = np.fromstring(signal, 'Int16')
    fs = spf.getframerate()

    Time=np.linspace(0, len(signal)/fs, num=len(signal))

    plt.figure(num=None, figsize=(6, 1.07), dpi=80, facecolor='w', edgecolor='k')
    plt.axis('off')
    plt.plot(Time, signal, color='#2ecc71')
    audio_spectrum_path = os.path.join(storage, audio_name+'.png')
    plt.savefig(audio_spectrum_path, transparent=True)
    plt.clf()
    return audio_spectrum_path


def audio_duration(audio_path):
    with contextlib.closing(wave.open(audio_path, 'r')) as f:
        frames = f.getnframes()
        rate = f.getframerate()
        duration = frames / float(rate)
        return str(duration).replace('.', ':')