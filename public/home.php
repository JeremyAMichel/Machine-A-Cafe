<?php

require_once "../utils/autoloader.php";

session_start();

if (!isset($_SESSION['machine'])) {
    $_SESSION['machine'] = new MachineACafe("Senseo");
}

/**
 * @var Machine $machine Instance of the Machine class stored in the session
 */
$machine = $_SESSION['machine'];
// var_dump($machine);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine à Café</title>
    <link rel="stylesheet" href="../asset/styles/style.css">
</head>

<body>
    <div class="container">
        <div class="coffee-header">

            <?php if ($machine->getNombreDossettes() > 0) : ?>
                <div id="btn-add-dosette" class="coffee-header_buttons dosette"></div>
            <?php else : ?>
                <div id="btn-add-dosette" class="coffee-header_buttons no_dosette"></div>
            <?php endif; ?>
            

            <?php if ($machine->getEstEnFonction()) : ?>
                <div id="btn-on-off" class="coffee-header_display on"></div>
            <?php else : ?>
                <div id="btn-on-off" class="coffee-header_display off"></div>
            <?php endif; ?>
            <div class="coffee-header_details"></div>
        </div>
        <div class="coffee-medium">
            <div class="coffee-medium_exit"></div>
            <div id="btn-make-coffee" class="coffee-medium_arm"></div>
            <div class="coffee-medium_liquid"></div>
            <div class="coffee-medium_smoke coffee-medium_smoke-one"></div>
            <div class="coffee-medium_smoke coffee-medium_smoke-two"></div>
            <div class="coffee-medium_smoke coffee-medium_smoke-three"></div>
            <div class="coffee-medium_smoke coffee-medium_smoke-four"></div>
            <div class="coffee-medium_cup"></div>
        </div>
        <div class="coffee-footer"></div>
    </div>

    <script>
        document.getElementById('btn-on-off').addEventListener('click', function() {
            sendAction('on_off');
        });

        document.getElementById('btn-add-dosette').addEventListener('click', function() {
            sendAction('add_dosette');
        });

        document.getElementById('btn-make-coffee').addEventListener('click', function() {
            sendAction('make_coffee');
        });

        function sendAction(action) {
            fetch('actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=' + action
                })
                .then(response => response.json())
                .then(data => {
                    // document.getElementById('response').innerText = data.message;
                    console.log(data);
                    if(data.action === 'on_off') {
                        document.getElementById('btn-on-off').classList.toggle('on');
                        document.getElementById('btn-on-off').classList.toggle('off');
                    }

                    if(data.action === 'add_dosette') {
                        if(data.message !== "Impossible d'ajouter une dosette") {
                            document.getElementById('btn-add-dosette').classList.toggle('dosette');
                            document.getElementById('btn-add-dosette').classList.toggle('no_dosette');
                        }   
                    }

                    if(data.action === 'make_coffee') {
                        if(data.message === "Café prêt") {
                            // Déclencher l'animation du café
                            document.querySelector('.coffee-medium_liquid').classList.add('active');

                            // Retirer l'animation une fois qu'elle est finie (8 secondes)
                            setTimeout(() => {
                                document.querySelector('.coffee-medium_liquid').classList.remove('active');
                            }, 8000);

                            // Retirer une dosette
                            document.getElementById('btn-add-dosette').classList.toggle('dosette');
                            document.getElementById('btn-add-dosette').classList.toggle('no_dosette');

                            // Déclencher l'animation de la fumée
                            document.querySelector('.coffee-medium_smoke-one').classList.add('active');
                            document.querySelector('.coffee-medium_smoke-two').classList.add('active');
                            document.querySelector('.coffee-medium_smoke-three').classList.add('active');
                            document.querySelector('.coffee-medium_smoke-four').classList.add('active');

                            // Retirer l'animation de smoke-one au bout de 21 secondes
                            setTimeout(() => {
                                document.querySelector('.coffee-medium_smoke-one').classList.remove('active');
                                
                            }, 21000);

                            // Retirer les autres animations au bout de 24 secondes
                            setTimeout(() => {
                                document.querySelector('.coffee-medium_smoke-two').classList.remove('active');
                                document.querySelector('.coffee-medium_smoke-three').classList.remove('active');
                                document.querySelector('.coffee-medium_smoke-four').classList.remove('active');
                            }, 24000);

                        }
                    }
                    
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

</body>

</html>