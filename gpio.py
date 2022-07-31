import RPi.GPIO as GPIO
from time import sleep
import asyncio
from websockets import connect

HOST = 'ws://192.168.1.30:8080'

GPIO.setmode(GPIO.BCM)
GPIO.setup(23, GPIO.IN)
GPIO.setup(12, GPIO.IN)

async def blue_count(uri):
    async with connect(uri) as websocket:
        await websocket.send('{"cmd": "count", "value": "blue"}')

async def red_count(uri):
    async with connect(uri) as websocket:
        await websocket.send('{"cmd": "count", "value": "red"}')

def send_blue_count(channel):
    asyncio.run(blue_count(HOST))

def send_red_count(channel):
    asyncio.run(red_count(HOST))

GPIO.add_event_detect(23, GPIO.RISING, callback = send_blue_count)

GPIO.add_event_detect(12, GPIO.RISING, callback = send_red_count)

try:
    while True:
        sleep(1)
except KeyboardInterrupt:
    GPIO.cleanup()

GPIO.cleanup()
