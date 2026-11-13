# Rozumím ti!

**Rozumím ti!** is a web app for practicing the two-handed Czech Sign Language alphabet. It asks you to make a sign in front of your camera, then uses an AI model to recognize it and give immediate feedback.

The project was created as a school project and entered in the Czech SOČ competition.

## Disclamer

Rozumím ti! is a supplementary practice tool, not a replacement for learning Czech Sign Language from Deaf signers or a qualified instructor. AI sign recognition may be inaccurate, so use feedback as a guide rather than a definitive assessment.

This project is an early-stage prototype and is currently configured for local use only.

## Features

- Randomized practice of the Czech two-handed alphabet.
- Camera-based hand and pose landmark detection with MediaPipe.
- Sign classification with a TensorFlow/Keras model.
- Immediate feedback as you work through the exercise.

## Technology

- PHP and MySQL for the web app and exercise data.
- JavaScript, MediaPipe Holistic, and Bootstrap in the browser.
- Python, Flask, TensorFlow, and NumPy for sign recognition.

## Project structure

```text
main/                 Flask recognition API and model/data scripts
models/               Trained Keras model
datasets/             Prepared training dataset
csv_data/             Sign landmark data
load-data/            Scripts for preparing sign data
web/                  PHP pages for the practice app
sql_databaze/         MySQL database export
requirements.txt      Python dependencies
```

## Running locally

The app expects a local PHP/MySQL environment. The PHP pages currently connect to a MySQL server at `localhost:8889` with database name `rozumim_ti_SQL` and the local `root` account. Update those connection settings in the PHP files if your environment differs.

1. Import [`sql_databaze/rozumim_ti_SQL.sql`](sql_databaze/rozumim_ti_SQL.sql) into MySQL.
2. Serve the `web/` directory with PHP through your local web server.
3. Install the Python dependencies listed in `requirements.txt` in a compatible Python environment.
4. From the project root, start the recognition API:

   ```bash
   python main/server.py
   ```

   The API listens on `http://127.0.0.1:5001`.
5. Open the web app through your local PHP server and allow camera access when prompted.

The browser page calls the recognition API at `http://127.0.0.1:5001/process_frame`. The current integration is configured for local development.

## Model and data scripts

- `main/csv_to_dataset.py` converts landmark CSV data into a NumPy dataset.
- `main/trainning.py` trains a classifier using that dataset.
- `main/server.py` receives landmark data from the browser and returns the predicted sign.

## Contributing data

The project welcomes contributions from Deaf people and certified Czech Sign Language teachers to help expand and improve the sign data and recognition model.

> **Note:** This project is not currently being actively developed.

## License

Does not exist...
