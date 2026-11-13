import csv
import numpy as np
from functions import normalize, prepare

# nastavení - jméno output souboru a případně separace
input_file = "csv_data/"
output_file = "dataset"
separation = ["abcdefgh1ijklmnopqrstuvwxyz"]
#separation = ["abcehilmpstw","fdg1kunorvxz"]

for i in range(len(separation)):
    samples = []
    labels = []
    # otevření csv a načtení dat
    with open(input_file, mode='r') as file:
        reader = csv.reader(file)
        for row in reader:
            left_hand = row[0]
            right_hand = row[1]
            nose = row[2]
            gesture = row[3]
            
            # rozdělení dat v případě separace
            if gesture in separation[i] and nose != "":
                l_hand = prepare(left_hand, 21)
                r_hand = prepare(right_hand, 21)
                nose = prepare(nose, 1)

                final, label = normalize(l_hand, r_hand, nose, gesture)
                samples.append(final)
                labels.append(label)

    # uložení do datasetu
    samples_final = np.array(samples)
    labels_final = np.array(labels)
    np.savez_compressed(f"{output_file}{i+1}.npz", data=samples_final, labels=labels_final)