import cv2
import csv
import mediapipe as mp

# nastavení - počet fotek, cesta ke složce s fotkami, typ fotek (musí být u všech stejný), jméno výstupního souboru
number_of_pictures = 3
folder_path = "load-data/pictures/"
file_type = ".jpg"
output_file = "raw_data.csv"

# iniciace mediapipe pro ruce
mp_hands = mp.solutions.hands
mp_drawing = mp.solutions.drawing_utils
hands = mp_hands.Hands(
    static_image_mode = True,
    max_num_hands = 2,
    min_detection_confidence = 0.5 
)

# iniciace mediapipe pro nos
mp_pose = mp.solutions.pose
pose = mp_pose.Pose(
    static_image_mode = True,
    min_detection_confidence = 0.5
)

# převod landmarků na string
def landmarks_to_string(landmarks):
    return ";".join([f"{lm.x}-{lm.y}" for lm in landmarks])

# hlavní cyklus
for i in range(211):
    # otevření fotky
    image_path = f"{folder_path}/{i+1}{file_type}"
    image = cv2.imread(image_path)

    # převod z BGR na RGB
    rgb_image = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)

    hand_result = hands.process(rgb_image)
    pose_result = pose.process(rgb_image)

    # získání landmarků z mediapipe a vykreslení do fotky
    left_hand_fingers = ""
    right_hand_fingers = ""
    nose = ""
    gesture = ""

    # landmarky rukou
    if hand_result.multi_hand_landmarks:
        for hand_landmarks, handedness in zip(hand_result.multi_hand_landmarks, hand_result.multi_handedness):
            label = handedness.classification[0].label
            if label == "Left":
                left_hand_fingers = landmarks_to_string(hand_landmarks.landmark)
            elif label == "Right":
                right_hand_fingers = landmarks_to_string(hand_landmarks.landmark)

        #nakreslení landmarků do obrázků
        for hand_no, hand_landmarks in enumerate(hand_result.multi_hand_landmarks):
            mp_drawing.draw_landmarks(image, hand_landmarks, mp_hands.HAND_CONNECTIONS)

    # landmarky nosu
    if pose_result.pose_landmarks:
        lm = pose_result.pose_landmarks.landmark
        nose = f"{lm[0].x}-{lm[0].y}"
        
    # zobrazení snímku
    cv2.imshow("Image", image)
    key = cv2.waitKey(0) & 0xFF

    # ESC = skip
    if key == 27:
        print(f"image {i+1}: skipped")
        continue

    # 9 = quit
    if key == ord('9'):
        print("Ended by user")
        break

    # any key = label pro gesto
    gesture = chr(key)
    print(f"Image {i+1}: label = '{gesture}'")

    # zápis dat do csv
    with open("raw_data.csv", "a", newline="") as file:
        writer = csv.writer(file)
        writer.writerow([left_hand_fingers, right_hand_fingers, nose, gesture])

# ukončení
cv2.destroyAllWindows()
hands.close()
pose.close()