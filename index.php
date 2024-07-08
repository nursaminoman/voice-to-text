<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="voice-to-text.jpg" type="image/x-icon" rel="icon">
    <meta property="og:image" content="voice-to-text.jpg"/>
    <meta name="author" content="Md. Nur Sami Noman">
    <title>Voice To Text</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        header {
            background: linear-gradient(to right, #009FFF, #ec2F4B);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 10px 0;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
        }
        header img {
            margin-right: 20px;
        }
        footer {
            background: linear-gradient(to right, #009FFF, #ec2F4B);
            color: white;
            text-align: center;
            padding: 0px 0;
            border-top-left-radius: 50px;
            border-top-right-radius: 50px;
        }
        .container {
            width: 100%;
            max-width: 90%;
            margin: 20px auto;
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 25px;
            overflow: hidden;
            background-color: white;
            padding: 10px;
        }
        #text-input {
            flex-grow: 1;
            padding: 15px;
            font-size: 30px;
            border: none;
            outline: none;
        }
        #text-input:focus {
            border-color: #007BFF;
        }
        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 50px;
            padding: 15px;
        }
        .select2-container--default .select2-selection--single {
            height: 100%;
            padding: 6px 6px;
            border: none;
            font-size: 18px;
            outline: none;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
        }
        .select2-container {
            margin-right: 10px;
            border-left: 1px solid #ccc;
            border-right: 1px solid #ccc;
        }
    }
    </style>
</head>
<body>
    <header>
        <img src="voice-to-text.jpg" width="50" height="50">
        <h1>Voice To Text</h1>
    </header>

    <div class="container">
        <input type="text" id="text-input" placeholder="Type or speak to input text..." autofocus>
        <select id="lang">
            <option value="en-US">English (United States)</option>
            <option value="en-GB">English (United Kingdom)</option>
            <option value="es-ES">Spanish (Spain)</option>
            <option value="es-MX">Spanish (Mexico)</option>
            <option value="fr-FR">French (France)</option>
            <option value="fr-CA">French (Canada)</option>
            <option value="de-DE">German (Germany)</option>
            <option value="it-IT">Italian (Italy)</option>
            <option value="ja-JP">Japanese (Japan)</option>
            <option value="ko-KR">Korean (South Korea)</option>
            <option value="zh-CN">Chinese (Simplified, China)</option>
            <option value="zh-TW">Chinese (Traditional, Taiwan)</option>
            <option value="pt-PT">Portuguese (Portugal)</option>
            <option value="pt-BR">Portuguese (Brazil)</option>
            <option value="ru-RU">Russian (Russia)</option>
            <option value="ar-SA">Arabic (Saudi Arabia)</option>
            <option value="hi-IN">Hindi (India)</option>
            <option value="bn-BD">Bengali (Bangladesh)</option>
            <option value="bn-IN">Bengali (India)</option>
        </select>
        <button id="record-btn" class="icon-btn" onclick="toggleRecording()">
            <i class="fa fa-microphone" aria-hidden="true"></i>
        </button>
    </div>

    <footer>
        <p>&copy; Copyright Voice To Text</p>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>

        $(document).ready(function() {
            $('#lang').select2();
        });

        let recognition;
        let isRecording = false;

        function toggleRecording() {
            if (isRecording) {
                stopRecording();
            } else {
                startRecording();
            }
        }

        function startRecording() {
            if (!('webkitSpeechRecognition' in window)) {
                alert('Your browser does not support speech recognition. Please use Google Chrome Or other browser.');
                return;
            }

            recognition = new webkitSpeechRecognition();
            recognition.continuous = true;
            recognition.interimResults = false;
            const languageSelect = document.getElementById('lang');
            recognition.lang = languageSelect.value; // Set language based on selection

            recognition.onstart = function () {
                isRecording = true;
                document.getElementById('record-btn').innerHTML = '<i class="fa fa-microphone" style="color:red;" aria-hidden="true"></i>'; // Stop icon
            };

            recognition.onresult = function (event) {
                let transcript = '';
                for (let i = event.resultIndex; i < event.results.length; ++i) {
                    transcript += event.results[i][0].transcript;
                }
                const inputField = document.getElementById('text-input');
                inputField.value += transcript;
                sendTranscriptToServer(inputField.value);
            };

            recognition.onerror = function (event) {
                console.error('Speech recognition error detected: ' + event.error);
            };

            recognition.onend = function () {
                isRecording = false;
                document.getElementById('record-btn').innerHTML = '<i class="fa fa-microphone" aria-hidden="true"></i>'; // Microphone icon
            };

            recognition.start();
        }

        function stopRecording() {
            if (isRecording) {
                recognition.stop();
            }
        }

        function sendTranscriptToServer(transcript) {
            fetch('save_transcript.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ transcript: transcript })
            })
            .then(response => response.text())
            .then(data => console.log(data))
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
