import threading
import time
import cv2
import RPi.GPIO as GPIO
#GPIO.setmode(GPIO.BOARD)
import requests
from pyzbar.pyzbar import decode
from picamera2 import Picamera2

# === NFC ===
import board
import busio
import adafruit_pn532.i2c
#GPIO.cleanup()
if GPIO.getmode() is None:
    GPIO.setmode(GPIO.BCM)
elif GPIO.getmode() != GPIO.BCM:
    raise RuntimeError(f"GPIO mode already set to something else!{GPIO.getmode()}-{GPIO.BCM}")



GPIO.cleanup()

# Initialize I2C NFC Reader (PN532)
i2c = busio.I2C(board.SCL, board.SDA)
pn532 = adafruit_pn532.i2c.PN532_I2C(i2c, debug=False)

# Configure PN532
pn532.SAM_configuration()

#==== Server config ====
Base_URL="http://172.20.10.10:8080/api"

# start a session
session = requests.Session()

# get the csrf token
#token_response = session.get(f'{Base_URL}/csrf-token')
csrf_token = None
#csrf_token = token_response.json().get('csrf_token')

# set the headers
headers = None

def fetch_csrf_token():
    global csrf_token, session,headers
    
    while csrf_token is None:
        try:
            print("Attempting to fetch CSRF token...")
            
            GPIO.output(RED_LED,GPIO.LOW) 
            response = session.get(f'{Base_URL}/csrf-token')
            csrf_token = response.json().get('csrf_token')
            # set the headers
            headers = {
            'X-CSRF-TOKEN': csrf_token,
            'Accept': 'application/json'
            }
        except requests.exceptions.RequestException as e:
            print("Error fetching CSRF token:, retrying in 3 seconds...")
            GPIO.output(RED_LED, GPIO.HIGH)
            time.sleep(3)


# === GPIO Setup ===
WHITE_LED = 18
RED_LED = 27
GREEN_LED = 17
BLUE_LED = 22
SENSOR_PIN = 23

#if GPIO.getmode() is None:
#    GPIO.setmode(GPIO.BOARD)
#elif GPIO.getmode() != GPIO.BOARD:
#    raise RuntimeError(f"GPIO mode already set to something else!{GPIO.getmode()}")

#GPIO.setmode(GPIO.BOARD)
GPIO.setup(SENSOR_PIN, GPIO.IN)
GPIO.setup(WHITE_LED, GPIO.OUT)
GPIO.setup(RED_LED, GPIO.OUT)
GPIO.setup(GREEN_LED, GPIO.OUT)
GPIO.setup(BLUE_LED, GPIO.OUT)
# === State Flags ===
proximity_detected = False
white_led_on = False
qr_data = None

# === Initialize Picamera2 ===
#picam2 = Picamera2()
#picam2.configure(picam2.create_video_configuration(main={"size": (640, 480)}))
#picam2.start()
picam2 = Picamera2()
picam2.preview_configuration.main.size = (320,240) #(640,480) # (320,240)
picam2.preview_configuration.main.format = "RGB888"
picam2.preview_configuration.align()
picam2.configure("preview")
picam2.start()



def handle_proximity():
    global white_led_on, qr_data
    while True:
        if GPIO.input(SENSOR_PIN) and not white_led_on and csrf_token!=None:
            white_led_on = True
            qr_data = None
            GPIO.output(WHITE_LED, GPIO.HIGH)
            time.sleep(3)
            GPIO.output(WHITE_LED, GPIO.LOW)
            white_led_on = False
        time.sleep(0.1)

#def scan_qr_code():
#    global qr_data
#    while True:
#        if white_led_on:
#            frame = picam2.capture_array()
#            decoded_objects = decode(frame)
#            for obj in decoded_objects:
#                qr_data = obj.data.decode('utf-8')
#                break
#        time.sleep(0.1)

def scan_qr_code():
    global qr_data, white_led_on 
    while True:
        if white_led_on or True:
            frame = picam2.capture_array()
#            cv2.imshow("Camera View", frame)
            
            decoded_objects = decode(frame)
            for obj in decoded_objects:
                qr_data = obj.data.decode('utf-8')
                print(f"data: {qr_data}") 
                break
#            time.sleep(2)
#            if cv2.waitKey(1) & 0xFF == ord('q'):
#                break
        else:
            time.sleep(0.1)

#    cv2.destroyAllWindows()

def set_led_feedback():
    global qr_data, white_led_on
    while True:
        if white_led_on:
            GPIO.output(BLUE_LED, GPIO.HIGH)
            GPIO.output(GREEN_LED, GPIO.LOW)
            GPIO.output(RED_LED, GPIO.LOW)
        else:
            GPIO.output(BLUE_LED, GPIO.LOW)
        if qr_data:
            GPIO.output(BLUE_LED, GPIO.LOW)
            if validate_qr_code(qr_data):
#                 white_led_on = False
                GPIO.output(GREEN_LED, GPIO.HIGH)
                GPIO.output(RED_LED, GPIO.LOW)
            else:
                GPIO.output(GREEN_LED, GPIO.LOW)
                GPIO.output(RED_LED, GPIO.HIGH)
            time.sleep(3)
            GPIO.output(GREEN_LED, GPIO.LOW)
            GPIO.output(RED_LED, GPIO.LOW)
            qr_data = None
            white_led_on = False
        time.sleep(0.1)

def validate_qr_code(data):
    try:
        print("validating data: ",data)
#        return True
        response = session.post(f"{Base_URL}/presenceViaQRCode", data={"cne": data}, headers=headers)
        print(response.json())
        return response.status_code == 200
    except requests.exceptions.RequestException as e:
        print(f"HTTP Error: {e}")
        return False


def read_nfc_tag():
    print("NFC thread started. Waiting for a tag...")
    while True:
        uid = pn532.read_passive_target(timeout=0.5)
        if uid:
            GPIO.output(GREEN_LED, GPIO.HIGH)
            time.sleep(.2)
            GPIO.output(GREEN_LED, GPIO.LOW)
            time.sleep(.2)
            GPIO.output(GREEN_LED, GPIO.HIGH)
            time.sleep(.2)
            GPIO.output(GREEN_LED, GPIO.LOW)

            tag_id = ''.join(['%02X' % i for i in uid])
            print(f"NFC Tag Detected: {tag_id}")
            #send_nfc_http(tag_id)
            time.sleep(2)  # Avoid duplicate reads
        time.sleep(0.1)

def send_nfc_http(tag_id):
    try:
        response = requests.post("http://yourserver.com/nfc", json={"nfc_uid": tag_id})
        print(f"Sent NFC UID, status: {response.status_code}")
    except requests.exceptions.RequestException as e:
        print(f"NFC HTTP error: {e}")

# === Start Threads ===
threading.Thread(target=fetch_csrf_token, daemon=True).start()
threading.Thread(target=handle_proximity, daemon=True).start()
threading.Thread(target=scan_qr_code, daemon=True).start()
threading.Thread(target=set_led_feedback, daemon=True).start()
threading.Thread(target=read_nfc_tag, daemon=True).start()
# === Keep Main Thread Alive ===
try:
    while True:
        time.sleep(1)

except KeyboardInterrupt:
    print("Exiting...")

finally:
   # cap.release()
   # cv2.destroyAllWindows()
    picam2.close()
    GPIO.cleanup()