<?php ?>

<!DOCTYPE html>
<html>
<head>
    <title>Balloon Pumping Game</title>
    <link href="https://fonts.googleapis.com/css?family=Gloria+Hallelujah" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/svg.js/2.7.1/svg.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Press+Start+2P|Space+Mono" rel="stylesheet">
    
    <script>
        var timeRemaining = 3;          // Amount of time remaining for the countDown

        var timeToShowBalloon = 2000;   // Amount of time to show the monster
        var timeToHideBalloon = 3000;   // Amount of time to hide the monster

        var hideMonsterTimeout;         // Timeout id for hiding the monster

        var life = 3;                   // The player's life
        var life1;
        var life2;
        var life3;

        var draw;
        var balloon;
        var countText;

        var totalCoins = 0;
        var coin;
        var totalCoinsText;

        var currentCoin = 0;
        var currentCoinText;

        var text;
        var clicktimeout;

        var gameoverText; 

        var pop;

        function saveScore() {
            var date = new Date();
            date.setMonth(date.getMonth() + 10);
            var expires = "; expires=" + date.toGMTString();
            document.cookie = "score=" + totalCoins + expires + "; path=/";
        }

        function loadScore() {
            var cookiearray = document.cookie.split(';');
            for (var i = 0; i < cookiearray.length; i++) {
                var name = cookiearray[i].split('=')[0];
                var value = cookiearray[i].split('=')[1];
                if (name == "score") {
                    return value;
                }
            }
            return 0;
        }

        function drawGameBackground() {
            draw = SVG('game-area').size(1280, 720);
            var background = draw.image('tree.jpg', 1280, 720)
        }
        function drawGameLife() {
            life1 = draw.image('heart.png')
            life1.size(51, 51).move(20, 20)

            life2 = draw.image('heart.png')
            life2.size(51, 51).move(80, 20)

            life3 = draw.image('heart.png')
            life3.size(51, 51).move(140, 20)
        }

        function drawCollect() {
            // text = draw.text('COLLECT')
            /// text.center(900,350).font({ size: 40, fill: '#EBC859', family: 'Gloria Hallelujah' });
            // text.click(function() {
            //     text.hide();
            //     balloon.stop();
            //     balloon.hide();
            //     balloon.attr({ rx: 20, ry: 25});
            //     clicktimeout = setTimeout(animateBalloon, 2000);
            //     currentCoinText.hide();
            //     totalCoins += currentCoin;
            //     totalCoinsText.text(totalCoins.toString());
            //     currentCoin = 0;
            // })
            $("#collectCoins").show();
            
        }

        function drawBalloon() {
            balloon = draw.ellipse(20, 25).move(600, 300).fill('#FBCADC');
            // var line = draw.line(0, 0, 0, 80).move(620, 325)
            // line.stroke({ color: '#ff7f00', width: 5, linecap: 'round' })
        }
        
        function countDown() {
            // Decrease the remaining time
            timeRemaining = timeRemaining - 1;
            // Continue the countDown if there is still time;
            // otherwise, start the game when the time is up
            if (timeRemaining > 0) {
                setTimeout(countDown, 1000);
                countText.text(timeRemaining.toString())
            }
            if (timeRemaining == 0) {
                countText.text("Start!")
                // countText.center(500,200).font({ size: 100, fill: '#ffa500', family: 'Gloria Hallelujah' });
                countText.move(500, 180);
                setTimeout(function() {
                    countText.hide()
                    setTimeout(function() {startGame();}, 200);
                }, 500)
                
            }
        }
        function showHighScore() {
            var scoreRetrieved = loadScore();
            $("#highScore").text("High Score: ".concat(scoreRetrieved));
            $("#highScore").show();
        }
        
        function startGame() {
            loadScore();
            drawBalloon();
            drawGameLife();
            drawTotalCoin();
            showHighScore();
            setTimeout(function() {
                drawCollect();
                animateBalloon();
            }, 200);
        }

        function changeLife() {
            life = life - 1;
            console.log(life);
            if (life == 2) {
                life3.hide();
                setTimeout(animateBalloon, 2000);
            }
            if (life == 1) {
                life2.hide();
                setTimeout(animateBalloon, 2000);
            }
            // If the game is over show the game over screen
            if (life == 0) {
                life1.hide();
                $("#collectCoins").hide()
                // text.hide();
                clearTimeout(clicktimeout)
                $("#highScore").hide();
                if (totalCoins > loadScore()) {
                    saveScore();
                    setTimeout(function() {
                        drawGameOver();
                        $('#restart-button').show();
                        $('#newHighScore').show();
                    }, 300);
                } else {
                    setTimeout(function() {
                        drawGameOver();
                        $('#restart-button').show();
                    }, 300);
                }
            }
        }
        function drawGameOver() {
            gameoverText = draw.text("GAME OVER")
            gameoverText.center(400,200).font({ size: 100, fill: '#ff0000', family: 'Gloria Hallelujah' });
        }

        function drawTotalCoin() {
            coin = draw.image('coin.png')
            coin.size(51, 51).move(960, 20)
            totalCoinsText = draw.text(totalCoins.toString());
            // totalCoinsText.center(1050, 30).font({ size: 30, fill: '#EBC859', family: 'Gloria Hallelujah' })
            totalCoinsText.attr({x:1020, y:20}).font({ size: 30, fill: '#EBC859', family: 'Gloria Hallelujah' })
        }

        function animateBalloon() {
            balloon.show();
            // text.show();
            $("#collectCoins").show();
            var amout = "$".concat(currentCoin.toString())
            currentCoinText = draw.text(amout)
            currentCoinText.attr({x:550, y:20}).font({ size: 70, fill: '#EBC859', family: 'Gloria Hallelujah' })
            var explodeAt = Math.random();
            // var explodeAt = 0.4;
            // if (explodeAt < 0.2) explodeAt = 0.2;
            // console.log(explodeAt);
            balloon.animate(5000).attr({ rx: 80, ry: 100}).during(function(pos, morph, eased, situation) {
                currentCoin = Math.floor(pos * 1000);
                var amt = "$".concat(currentCoin.toString())
                currentCoinText.text(amt);
                if (pos > explodeAt) {
                    // text.hide();
                    $("#collectCoins").hide()
                    balloon.stop();
                    balloon.hide();
                    balloon.attr({ rx: 20, ry: 25});
                    // var pop = draw.image('pop1.png', 300, 300).move(460, 180);
                    pop.show();
                    setTimeout(function() {
                        pop.hide();
                    }, 300);
                    currentCoinText.hide();
                    currentCoin = 0;
                    changeLife();
                }
            });
        }

        function startCountingDown() {
            drawGameBackground();
            pop = draw.image('pop1.png', 300, 300).move(460, 180);
            pop.hide();
            countText = draw.text(timeRemaining.toString())
            countText.center(600,200).font({ size: 100, fill: '#E75345', family: 'Gloria Hallelujah' });
            setTimeout(countDown, 1000);
            // console.log(balloon.attr());
        }

        function restart() {
            countText = draw.text(timeRemaining.toString())
            countText.center(600,200).font({ size: 100, fill: '#E75345', family: 'Gloria Hallelujah' });
            setTimeout(countDown, 1000);
        }

        $(document).ready(function () {
            $('#start-button').on('click', function() {
                $('#start-area').hide();
                $('#game-area').show();
                startCountingDown();
            });

            $('#restart-button').on('click', function() {
                $('#restart-button').hide();
                $('#newHighScore').hide();
                timeRemaining = 3;
                totalCoins = 0;
                currentCoin = 0;
                life = 3;
                gameoverText.hide();
                coin.hide();
                totalCoinsText.hide();
                restart();
            });

            $("#collectCoins").on('click', function() {
                $("#collectCoins").hide();
                balloon.stop();
                balloon.hide();
                balloon.attr({ rx: 20, ry: 25});
                clicktimeout = setTimeout(animateBalloon, 2000);
                currentCoinText.hide();
                totalCoins += currentCoin;
                totalCoinsText.text(totalCoins.toString());
                currentCoin = 0;
            })
        });
    </script>
    <style>
        /* Style to make the svg fit in the browser */
        body { padding: 0; margin: 0; }
        svg  { width: 100%; height: auto; }
        #start-area {
            /* width: 90vmin;
            height: 90vmin;*/
            /* position: relative; */
            /* margin: 5vmin auto; */
        }
        #start-button {
            position: absolute;
            top: 50%;
            left: 38%;
            font-family: 'Press Start 2P', cursive;
            /* color: #EBC859 */
        }
        #game-name {
            position: absolute;
            top: 20%;
            left: 25%;
            font-family: 'Press Start 2P', cursive;
            color: #E75345;
        }
        #restart-button {
            position: absolute;
            top: 60%;
            left: 45%;
            font-family: 'Press Start 2P', cursive;   
        }
        #collectCoins {
            position: absolute;
            bottom: 50%;
            right: 20%;
            font-family: 'Press Start 2P', cursive;
            /* color: #EBC859; */
        }
        #highScore {
            position: absolute;
            top: 10%;
            left: 70%;
            font-family: 'Press Start 2P', cursive;
            color: #EBC859;
        }
        #newHighScore {
            position: absolute;
            /* top: 5%;
            left: 40%; */
            top: 10%;
            left: 70%;
            font-family: 'Press Start 2P', cursive;
            color: #EBC859;
        }
    </style>
</head>
<body> 
    <div id="game-area" style="display: none"></div>
    <div id="start-area">
        <img src="tree.jpg" width="1280" height="720">
        <h1 id="game-name">BALLOON PUMPING GAME</h1>
        <button id="start-button"><p>PRESS TO START THE GAME</p></button>
    </div>
    <div>
        <button id="restart-button" style="display: none"><p>Restart</p></button>
    </div>
    <div>
        <button id="collectCoins" style="display: none"><p>COLLECT COINS</p></button>
        <h3 id="highScore" style="display: none"></h3>
        <h3 id="newHighScore" style="display: none">NEW HIGH SCORE!</h3>
    </div>
</body>
</html>
