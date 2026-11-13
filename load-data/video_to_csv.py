import cv2
import csv
import mediapipe as mp

# nastavení - cesta k videu, počet snímků k přeskočení, jméno výstupního souboru
video_path = "load-data/"
frames_skip = 10                            
output_file = "raw_data.csv"

# iniciace mediapipe pro ruce
mp_hands = mp.solutions.hands
mp_drawing = mp.solutions.drawing_utils
hands = mp_hands.Hands(
    static_image_mode=True,
    max_num_hands=2,
    min_detection_confidence=0.5
)

# iniciace mediapipe pro nos
mp_pose = mp.solutions.pose
pose = mp_pose.Pose(
    static_image_mode=True,
    min_detection_confidence=0.5
)

# převod landmarků na string
def landmarks_to_string(landmarks):
    return ";".join([f"{lm.x}-{lm.y}" for lm in landmarks])

# otevření videa
cap = cv2.VideoCapture(video_path)

if not cap.isOpened():
    print(f"Video can not be opened: {video_path}")
    exit(1)

total_frames = int(cap.get(cv2.CAP_PROP_FRAME_COUNT))
fps = cap.get(cv2.CAP_PROP_FPS)

# hlavní cyklus
frame_idx = 0
while True:
    # otevření fotky
    cap.set(cv2.CAP_PROP_POS_FRAMES, frame_idx)
    ret, image = cap.read()

    if not ret or frame_idx >= total_frames:
        print("Video ended.")
        break

    # převod z BGR na RGB
    rgb_image = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)

    hand_result = hands.process(rgb_image)
    pose_result = pose.process(rgb_image)

    # získání landmarků z mediapipe a vykreslení do fotky
    left_hand_fingers = ""
    right_hand_fingers = ""
    nose = ""

    # landmarky rukou
    if hand_result.multi_hand_landmarks:
        for hand_landmarks, handedness in zip(hand_result.multi_hand_landmarks, hand_result.multi_handedness):
            label = handedness.classification[0].label
            if label == "Left":
                left_hand_fingers = landmarks_to_string(hand_landmarks.landmark)
            elif label == "Right":
                right_hand_fingers = landmarks_to_string(hand_landmarks.landmark)

        #nakreslení landmarků do obrázků
        for hand_landmarks in hand_result.multi_hand_landmarks:
            mp_drawing.draw_landmarks(image, hand_landmarks, mp_hands.HAND_CONNECTIONS)

    # landmarky nosu
    if pose_result.pose_landmarks:
        lm = pose_result.pose_landmarks.landmark
        nose = f"{lm[0].x}-{lm[0].y}"

    # Timestamp
    timestamp = frame_idx / fps if fps > 0 else 0

    # zobrazení snímku
    cv2.imshow("Labeling", image)
    key = cv2.waitKey(0) & 0xFF

    # ESC = skip
    if key == 27:
        print(f"  Frame {frame_idx} ({timestamp:.2f}s): skipped")
        frame_idx += frames_skip
        continue

    # 9 = quit
    if key == ord('9'):
        print("Ended by user")
        break

    # any key = label pro gesto
    gesture = chr(key)
    print(f"Frame {frame_idx} ({timestamp:.2f}s): label = '{gesture}'")

    # zápis dat do csv
    with open(output_file, "a", newline="") as file:
        writer = csv.writer(file)
        writer.writerow([left_hand_fingers, right_hand_fingers, nose, gesture])

    frame_idx += frames_skip

# ukončení
cap.release()
cv2.destroyAllWindows()
hands.close()
pose.close()