import cv2
from ultralytics import YOLO
from dotenv import load_dotenv
import os,time, threading
import requests


# Load environment variables from .env file
load_dotenv()

# Access variables
ip_address = os.getenv("IP_ADDRESS")
port = os.getenv("PORT")
nom_salle = os.getenv("ROOM_NAME")




#==== Server config ====
Base_URL=f"http://{ip_address}:{port}/api"

# start a session
session = requests.Session()

# get the csrf token
#token_response = session.get(f'{Base_URL}/csrf-token')
csrf_token = None
#csrf_token = token_response.json().get('csrf_token')

# set the headers
headers = None
session_id= None
def fetch_csrf_token():
    global csrf_token, session,headers
    
    while csrf_token is None:
        try:
            print("Attempting to fetch CSRF token...")
            # print(Base_URL)
            response = session.get(f'{Base_URL}/csrf-token', timeout=5)
            # print("done",response)

            csrf_token = response.json().get('csrf_token')
            # print("done",csrf_token)
            # set the headers
            headers = {
            'X-CSRF-TOKEN': csrf_token,
            'Accept': 'application/json'
            }
            print("done",csrf_token)
        except Exception as e:
            print("Error fetching CSRF token:, retrying in 3 seconds...")
            print(e)
            time.sleep(3)


def update_nb_students(count):
    try:
        data={
            "session_id": session_id,
            "nom_salle":nom_salle,
            "ai_detect":count
        }
#        return True
        response = session.post(f"{Base_URL}/update-nb-student", data=data, headers=headers)
        print(response.json())
        return response.status_code == 200
    except requests.exceptions.RequestException as e:
        print(f"HTTP Error: {e}")
        return False


def get_next_session_id():
    try:
        response = requests.get(f"{Base_URL}/get-today-next-sessions", params={'nom_salle': nom_salle})

        if response.status_code == 200:
            session_data = response.json()
            id=session_data.get('id')
            print("id = ",id)
            return id  # Return only the session ID
        else:
            return None  # Could add logging or error info here

    except requests.exceptions.RequestException:
        return None


# GStreamer pipeline function
def xgstreamer_pipeline(
    capture_width=1280,
    capture_height=720,
    display_width=320,
    display_height=240,
    framerate=30,
    flip_method=0,
):
    return (
        f"nvarguscamerasrc !"
        f"video/x-raw(memory:NVMM), width={capture_width}, height={capture_height}, "
        f"format=NV12, framerate={framerate}/1 ! "
        f"nvvidconv flip-method={flip_method} ! "
        f"video/x-raw, width={display_width}, height={display_height}, format=BGRx ! "
        f"videoconvert ! video/x-raw, format=BGR ! appsink"
    )

def gstreamer_pipeline(
    capture_width=1280,
    capture_height=720,
    display_width=1280,
    display_height=720,
    framerate=30,
    flip_method=0
):
    return (
        f"nvarguscamerasrc ! video/x-raw(memory:NVMM), "
        f"width={capture_width}, height={capture_height}, "
        f"format=NV12, framerate={framerate}/1 ! "
        f"nvvidconv flip-method={flip_method} ! "
        f"video/x-raw, width={display_width}, height={display_height}, format=BGRx ! "
        f"videoconvert ! video/x-raw, format=BGR ! appsink"
    )

  # Load YOLOv8 model
model = YOLO('../yolov8n.pt')
# cap = cv2.VideoCapture(gstreamer_pipeline(), cv2.CAP_GSTREAMER)
cap = cv2.VideoCapture(gstreamer_pipeline(), cv2.CAP_GSTREAMER)

# === Start Threads ===
# threading.Thread(target=count_students, daemon=True).start()
threading.Thread(target=fetch_csrf_token, daemon=True).start()

# def count_students():
    # global session_id



# Open camera with GStreamer pipeline
if not cap.isOpened():
    print("Error: Unable to open camera.")
    exit()
ret, frame = cap.read()
session_id=get_next_session_id()
if not ret:
    print("Error: Frame capture failed.")
    #break

else:
#    cv2.imshow('Student Counting', frame)
    while True:
        
        if(session_id !=None):
    

            results = model(frame)
            detections = results[0].boxes.data.cpu().numpy()
            count = 0

            for det in detections:
                class_id = int(det[5])
                if class_id == 0:
                    count += 1
                    x1, y1, x2, y2 = map(int, det[:4])
      #              cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 255, 0), 2)
     #               cv2.putText(frame, 'Person', (x1, y1 - 10),
    #                            cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)

  #          cv2.putText(frame, f'Student Count: {count}', (10, 30),
   #                     cv2.FONT_HERSHEY_SIMPLEX, 1.0, (255, 0, 0), 2)

 #           cv2.imshow('Student Counting', frame)
            
            update_nb_students(count)
            time.sleep(600) # wait for 10 minutes
            # time.sleep(10) # wait for 10 seoncds

       #     if cv2.waitKey(1) & 0xFF == ord('q'):
        #        break
            session_id=get_next_session_id()
        else:
            session_id=get_next_session_id()
            if not session_id:
                print("No session now, checking in 2 minutes")
            time.sleep(120) # wait for 2 minutes
            # time.sleep(5) # wait for 5 seconds
cap.release()
#cv2.destroyAllWindows()