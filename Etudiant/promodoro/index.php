<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pomodoro Timer</title>
    <?php include '../nav/navbar.html'; ?>
    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <!-- ICONS -->
    <script src="https://kit.fontawesome.com/6f3103b13c.js" crossorigin="anonymous"></script>

     
</head>
<body>
    <section>
        <div class="container">
             
            <div class="painel">
                <p id="work">work</p>
                <p id="break">break</p>
            </div>

            <div class="timer">
                <div class="circle">
                    <div class="time">
                        <p id="minutes"></p>
                        <p>:</p>
                        <p id="seconds"></p>
                    </div>
                </div>
            </div>

            <div class="controls">
                <button id="start" onclick="start()"><i class="fa-solid fa-play"></i></button>

                <a id="reset" href="./"><i class="fa-solid fa-arrow-rotate-left"></i></a>
            </div>
        </div>
    </section>

    <!-- SCRIPT -->
    <script src="main.js"></script>
</body>
</html>