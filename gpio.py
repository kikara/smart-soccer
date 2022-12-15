import RPi.GPIO as GPIO
from time import sleep
import asyncio
from websockets import connect

HOST = 'ws://localhost:8080'

GPIO.setmode(GPIO.BCM)
GPIO.setup(23, GPIO.IN)
GPIO.setup(12, GPIO.IN)
GPIO.setup(4, GPIO.IN, pull_up_down=GPIO.PUD_UP)

async def blue_count(uri):
    async with connect(uri) as websocket:
        await websocket.send('{"cmd": "count", "value": "blue"}')

async def red_count(uri):
    async with connect(uri) as websocket:
        await websocket.send('{"cmd": "count", "value": "red"}')

async def reset_goal(uri):
    async with connect(uri) as websocket:
        await websocket.send('{"cmd": "reset_last_goal"}')

def send_blue_count(channel):
    asyncio.run(blue_count(HOST))

def send_red_count(channel):
    asyncio.run(red_count(HOST))

def reset_last_goal(channel):
    asyncio.run(reset_goal(HOST))

GPIO.add_event_detect(23, GPIO.RISING, callback = send_blue_count)

GPIO.add_event_detect(12, GPIO.RISING, callback = send_red_count)

GPIO.add_event_detect(4, GPIO.FALLING, callback = reset_last_goal)

try:
    while True:
        sleep(1)
except KeyboardInterrupt:
    GPIO.cleanup()

GPIO.cleanup()
