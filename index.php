<?php

require 'config.php';

?>

<!DOCTYPE html>

<html lang="en">
<head>
    <title>Timer</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style type="text/css">
        body {
            overflow: hidden;
            height: 100%;
            background: #e3e3e3;
            margin: 0;
        }
        #center {
            margin: 0;
            text-align: center;
            line-height: 10vh;
        }
        h1 {
            font-family: helvetica;
            font-size: 8vw;
            line-size: 200px;
        }
        h2 {
            font-size: 170px;
        }
    </style>
    <script>
        description = "";
        duration = NaN;

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        function loadDoc() {
            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = async function() {
                if (this.readyState === 4) {
                    console.log("We are ready!");
                    data = JSON.parse(this.responseText); //get the data
                    console.log("Got data: " + data);
                    description = data[0];
                    duration = data[1];
                }
            };

            xhttp.open("GET", "timer.php", true);
            xhttp.send();
        }


        function formatSeconds(seconds) {
            var hours   = Math.floor(seconds / 3600);
            var minutes = Math.floor((seconds - (hours * 3600)) / 60);
            var seconds = Math.floor((seconds - (hours * 3600) - (minutes * 60)) * 1) / 1;
            var time = "";

            if (hours != 0) {
                time = hours+":";
            }
            if (minutes != 0 || time !== "") {
                minutes = (minutes < 10 && time !== "") ? "0"+minutes : String(minutes);
                time += minutes+":";
            }
            if (time === "") {
                time = seconds+"s";
            }
            else {
                time += (seconds < 10) ? "0"+seconds : String(seconds);
            }
            return time;
        };

        setInterval(function() {
          loadDoc();
        }, 1000);

        setInterval(function() {
            var description_span = document.getElementById('description_span');
            var duration_span = document.getElementById('duration_span');

            if (description === null) {
              description_span.innerHTML = "No current task";
              duration_span.innerHTML = "";
              return;
            }
            description_span.innerHTML = description; //show description

            var seconds = (new Date()).getTime() / 1000;
            seconds = seconds + duration;
            if(<?= $formatseconds ?>) {
                seconds = formatSeconds(seconds);
            }
            duration_span.innerHTML = seconds; //show duration
        }, 100);

        if(<?= $auto_reload ?>) {
            setTimeout(function() {
                window.location.reload();
            }, 10000);
        }
    </script>
</head>

<body>
    <header>
        <div id="center">
            <h1>
                <span id="description_span"></span>
            </h1>
            <h2>
                <span id="duration_span"></span>
            </h2>
        </div>
    </header>
</body>
</html>
