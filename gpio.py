import RPi.GPIO as GPIO
from time import sleep
import asyncio
from websockets import connect

GPIO.setmode(GPIO.BCM)
GPIO.setup(23, GPIO.IN)

async def hello(uri):
    async with connect(uri) as websocket:
        await websocket.send('{"count": "blue"}')


def print_state(channel):
    asyncio.run(hello("ws://192.168.1.30:8080"))

GPIO.add_event_detect(23, GPIO.RISING, callback = print_state)

try:
    while True:
        sleep(1)
        print('.')
except KeyboardInterrupt:
    GPIO.cleanup()

GPIO.cleanup()
