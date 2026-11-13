from flask import Flask, request, jsonify
from flask_cors import CORS
import numpy as np
from functions import normalize, predict

# spojení s webem pomocí flasku
app = Flask(__name__)
CORS(app)

# zpracování mediapipe z webu
def prepare_hand(hand_data):
    if not hand_data:
        return np.zeros((21, 2))
    
    points = [[p['x'], p['y']] for p in hand_data]
    return np.array(points)

def prepare_nose(nose_data):
    if not nose_data:
        return np.zeros((1, 2))
    
    return np.array([[nose_data['x'], nose_data['y']]])

@app.route('/process_frame', methods=['POST'])
def process_frame():
    try:
        # načtení dat z webu
        data = request.json
        model_name = data.get('model_name')

        def process_variant(d):
            nose = prepare_nose(d.get('nose'))
            left = prepare_hand(d.get('right_hand'))
            right = prepare_hand(d.get('left_hand'))

            normalized_frame, _ = normalize(left, right, nose, "")
            return normalized_frame
        
        frame = process_variant(data)
        prediction1, prop1 = predict(frame, model_name)

        # otočení snímku
        mirrored = {
            'nose': {'x': 1 - data['nose']['x'], 'y': data['nose']['y']} if data.get('nose') else None,
            'left_hand': [{'id': p['id'], 'x': 1 - p['x'], 'y': p['y']} for p in data.get('right_hand', [])],
            'right_hand': [{'id': p['id'], 'x': 1 - p['x'], 'y': p['y']} for p in data.get('left_hand', [])]
        }

        frame = process_variant(mirrored)
        prediction2, prop2 = predict(frame, model_name)

        # porování normálního a otočeného snímku
        if prop1 >= prop2:
            label = prediction1
            prop = prop1
        else:
            label = prediction2
            prop = prop2

        if prop < 0.8:
            return jsonify({"status": "success", "analysis": {"label": None}})

        # výstup v JSON
        response_data = {
            "status": "success",
            "message": "Data processed",
            "analysis": {
                "label": int(label)
            }
        }

        return jsonify(response_data)

    except Exception as e:
        print(f"Chyba: {e}")
        return jsonify({"status": "error", "message": str(e)}), 500

app.run(debug=True, port=5001)