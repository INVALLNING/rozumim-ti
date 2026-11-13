import tensorflow as tf
import keras
import numpy as np

# načtení datasetu
with np.load('datasets/dataset_alphabet.npz', allow_pickle=True) as loaded:
    X = loaded['data']
    y = loaded['labels']

# převedení na správný datový typ
X_train = X.astype("float32")
y_train = y.astype("int32")

# počet klasifikačních tříd
num_classes = 27

# inciace modelu
model = tf.keras.Sequential([
    tf.keras.layers.Input(shape=(88,)),
    tf.keras.layers.Dense(128, activation='relu'),
    tf.keras.layers.Dropout(0.2),
    tf.keras.layers.Dense(64, activation='relu'),
    tf.keras.layers.Dense(num_classes, activation='softmax')
])

# nastavení modelu
model.compile(
    optimizer='adam',
    loss='sparse_categorical_crossentropy',
    metrics=['accuracy']
)

# trénink modelu
model.fit(
    X_train,
    y_train,
    epochs=9,
    batch_size=32,
    validation_split=0.1
)

# uložení modelu
model.save('model_self_v2.keras')