import numpy as np
import tensorflow as tf
import keras
# aktavční command na zapnutí myenv
# venv: source /Applications/MAMP/htdocs/ZPITK/myenv/bin/activate

# první zpracování dat z mediapipe
def prepare(coordinates, size):
    if coordinates == None:
        return np.zeros((size, 2))
    else:
        points = [list(map(float, p.split('-'))) for p in coordinates.split(';') if p]
        return np.array(points)

# normalizace dat po funkci prepare
def normalize(l_hand, r_hand, nose, gesture):
    alphabet = "abcdefgh1ijklmnopqrstuvwxyz"

    if gesture != "":
        label = alphabet.index(gesture)
    else:
        label = ""

    def proccess(hand, nose):
        # odečtení vzdáleností od zápěstí
        wrist = hand[0]
        hand_shape = hand - wrist

        # poměr vzdálenosti od nejdelší vzdálenosti
        size = np.max(np.abs(hand_shape))
        if size > 0:
            hand_shape = hand_shape / size
            
        # získání vzdálenosti od nosu
        pos_rel = wrist - nose

        return hand_shape.flatten(), pos_rel.flatten()

    l_hand, l_pos = proccess(l_hand, nose)
    r_hand, r_pos = proccess(r_hand, nose)

    # spojení dat
    return np.concatenate([l_hand, r_hand, l_pos, r_pos]), label

# funkce pro předpověď
def predict(input, model_name):
    model = tf.keras.models.load_model(f"models/{model_name}")

    input = np.expand_dims(input, axis=0).astype('float32')

    predictions = model.predict(input, verbose=0)

    class_index = np.argmax(predictions)
    probability = predictions[0][class_index]

    return class_index, probability