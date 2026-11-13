<?php
    //propojení s php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo json_encode(["status" => "ok"]);
        exit;
    }

    require __DIR__ . "/template.php";

    //propojení s databází
    $conn = mysqli_connect('localhost:8889', 'root', 'root', 'rozumim_ti_SQL');

    if (!$conn) {
        die("Connection failed: ". mysqli_connect_error());
    }

    $sql = "SELECT id_excercise, name_excercise, name_model, descr, gestures FROM cviceni WHERE id_excercise = ".$_REQUEST["submit"];
    $result = mysqli_query($conn, $sql);
    $row = null;
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    }

    $gestures = [];
    if ($row && !empty($row["gestures"])) {
        $gestures = json_decode($row["gestures"], true);
    }
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="UTF-8">
        <title>Rozumím ti! - Cvičení</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@mediapipe/control_utils/control_utils.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@mediapipe/holistic/holistic.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <header>
            <?php _header(1); ?>
        </header>

        <div class="container mt-4">
            <div class="card bg-light p-4">
                <h1><?php echo $row ? htmlspecialchars($row["name_excercise"]) : ""; ?></h1>
                <p><?php echo $row ? htmlspecialchars($row["descr"]) : ""; ?></p>

                <div id="current-sign" class="text-center my-3">
                    <span class="display-4 fw-bold"></span>
                </div>
                <div id="progress" class="text-center text-muted mb-2"></div>

                <div style="margin: 0 auto; width: 50vw;">
                    <video style="display: none;" class="input_video"></video>
                    <canvas class="output_canvas" width="640" height="480" style="width: 100%; height: auto; display: block; transform: scaleX(-1);"></canvas>
                </div>

                <div id="output" class="text-center mt-3 fs-5">Čekám na data...</div>

                <button id="skip-btn" class="btn btn-warning mt-2" onclick="skipSign()">Přeskočit</button>
            </div>
        </div>

        <script>
            //data z php
            const modelName = "<?php echo $row ? addslashes($row['name_model']) : ''; ?>";
            const gestures = <?php echo json_encode($gestures); ?>;

            //zamýchání a fronta
            function shuffle(array) {
                return [...array].sort(() => Math.random() - 0.5);
            }

            let queue = shuffle(gestures);

            console.log("gestures:", gestures);
            console.log("queue:", queue);

            let currentIndex = 0;

            function showCurrentSign() {
                const signEl = document.querySelector('#current-sign span');
                const progressEl = document.getElementById('progress');

                if (currentIndex >= queue.length) {
                    signEl.innerText = "Hotovo!";
                    progressEl.innerText = "Všechny znaky splněny!";
                    document.getElementById('output').innerText = "";
                    document.getElementById('skip-btn').innerText = "Zpět na cvičení";
                    document.getElementById('skip-btn').className = "btn btn-primary mt-2";
                    document.getElementById('skip-btn').onclick = function() {
                        window.location.href = 'cviceni.php';
                    };
                    return;
                }

                signEl.innerText = "Ukaž: " + queue[currentIndex];
                progressEl.innerText = (currentIndex + 1) + " / " + queue.length;
                document.getElementById('output').innerText = "Čekám na gesto...";
            }

            showCurrentSign();

            function skipSign() {
                if (currentIndex < queue.length) {
                    currentIndex++;
                    showCurrentSign();
                }
            }

            //mediapipe
            const videoElement = document.getElementsByClassName('input_video')[0];
            const canvasElement = document.getElementsByClassName('output_canvas')[0];
            const canvasCtx = canvasElement.getContext('2d');
            const outputDiv = document.getElementById('output');
            let frameCount = 0;

            let isProcessing = false;
            let isPaused = false;

            async function sendToPython(payload) {
                if (isProcessing || isPaused) return;
                if (currentIndex >= queue.length) return;
                isProcessing = true;

                payload.model_name = modelName;

                try {
                    const response = await fetch('http://127.0.0.1:5001/process_frame', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();
                    
                    const recognizedIndex = result.analysis.label;
                    const recognizedSign = gestures[recognizedIndex];

                    if (recognizedSign === queue[currentIndex]) {
                        outputDiv.innerHTML = '<span class="text-success fw-bold">Správně! (' + recognizedSign + ')</span>';
                        currentIndex++;
                        isPaused = true;
                        setTimeout(() => {
                            isPaused = false;
                            showCurrentSign();
                        }, 3000);
                    } else {
                        outputDiv.innerHTML = '<span class="text-danger">Rozpoznáno: <strong>' + recognizedSign + '</strong></span>';
                    }

                } catch (error) {
                    console.error("Chyba při komunikaci:", error);
                    outputDiv.innerText = "Chyba spojení s Pythonem.";
                } finally {
                    isProcessing = false;
                }
            }

            function onResults(results) {
                canvasCtx.save();
                canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);
                canvasCtx.drawImage(results.image, 0, 0, canvasElement.width, canvasElement.height);
                canvasCtx.restore();

                let payload = {
                    nose: null,
                    left_hand: [],
                    right_hand: []
                };

                if (results.poseLandmarks) {
                    const nose = results.poseLandmarks[0];
                    payload.nose = { x: nose.x, y: nose.y };
                }

                if (results.leftHandLandmarks) {
                    payload.left_hand = results.leftHandLandmarks.map((point, index) => {
                        return { id: index, x: point.x, y: point.y };
                    });
                }

                if (results.rightHandLandmarks) {
                    payload.right_hand = results.rightHandLandmarks.map((point, index) => {
                        return { id: index, x: point.x, y: point.y };
                    });
                }

                if (payload.nose || payload.left_hand.length > 0 || payload.right_hand.length > 0) {
                    frameCount++;
                    if (frameCount % 2 === 0) {
                        sendToPython(payload);
                    }
                }
            }

            const holistic = new Holistic({locateFile: (file) => {
                return `https://cdn.jsdelivr.net/npm/@mediapipe/holistic/${file}`;
            }});

            holistic.setOptions({
                modelComplexity: 1,
                smoothLandmarks: true,
                enableSegmentation: false,
                smoothSegmentation: false,
                refineFaceLandmarks: false,
                minDetectionConfidence: 0.5,
                minTrackingConfidence: 0.5
            });

            holistic.onResults(onResults);

            const camera = new Camera(videoElement, {
                onFrame: async () => {
                    await holistic.send({image: videoElement});
                },
                width: 640,
                height: 480
            });
            camera.start();
        </script>
    </body>
</html>