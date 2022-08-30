from pydub import AudioSegment
from os.path import exists
import sys
import re

def match_target_amplitude(sound):
    return sound.apply_gain(-3.0)

    
if len(sys.argv) == 2:
    audioPath = sys.argv[1]
    ifexsist = exists(audioPath)
    if re.search('.mp3',audioPath):
        if ifexsist:
            sound = AudioSegment.from_file(audioPath, "mp3")
            normalized_sound = match_target_amplitude(sound)
            normalized_sound.export(audioPath, format="mp3")
            print(audioPath)
        else:
            print('Error: File not found')
    else:
        print('Error: Format not suported')
else:
    print("Error: You should send file name after scriptname")
