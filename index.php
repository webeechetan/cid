<?php
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['token'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CID</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Anton' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body>
    <!--- Top Banner --->
    <section class="main-banner">
        <div class="main-child-banner"></div>
        <div class="main-cid-banner">
            <img id="cidImage" src="./assets/images/people.png" alt="CID">
        </div>
        <div class="banner-text">
            <h1><span class="main-text">DAYA!</span>Kuch Toh Gadbad Hai.</h1>
        </div>
    </section>
    <section class="cross-images"><img src="./assets/images/bg-yellow.png" alt=""></section>
    <section class="video-banner">
        <div class="video-img-banner">

        </div>
        <div class="video-img">
            <iframe src="https://www.youtube.com/embed/84lXptaudOU?si=EjMIow9fnvRvX8Zo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>
    </section>
    <section class="bottom-banner sec-space">
        <div class="banner-bottom-poll">
            <h2>Would you like to<br> to have a <span>CID Reboot?</span></h2>
        </div>

        <form method="POST" class="poll-form">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <div class="poll_btn">
                <div class="switch">
                    <input type="radio" name="vote" value="yes" checked>
                    <div class="switch-wrap">
                        <div class="switch-icon"><i class='bx bxs-check-circle'></i></div>
                        <div class="switch-text">Yes</div>
                    </div>
                </div>
                <div class="switch switch-no">
                    <input type="radio" name="vote" value="no">
                    <div class="switch-wrap">
                        <div class="switch-icon"><i class='bx bxs-check-circle'></i></div>
                        <div class="switch-text">No</div>
                    </div>
                </div>
            </div>
            <div class="custom-form">
                <div>
                    <input type="text" name="name" placeholder="Name" value="name">
                </div>
                <div>
                    <input type="email" name="email" placeholder="Email" value="email">
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        </div>
        </div>
    </section>

    <!-- Js --->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="./assets/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.4/howler.min.js" integrity="sha512-xi/RZRIF/S0hJ+yJJYuZ5yk6/8pCiRlEXZzoguSMl+vk2i3m6UjUO/WcZ11blRL/O+rnj94JRGwt/CHbc9+6EA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {


            var sound = new Howl({
                src: ['assets/audio/police-siren.wav']
            });

            var constraints = { audio: true } // add video constraints if required

            navigator.mediaDevices.getUserMedia(constraints)
            .then((stream) => {
                var audioContext = new AudioContext();
                sound.play();

            }).catch((err) => {
                console.log(err)
            })


            $(".poll-form").submit(function(e) {
                e.preventDefault();
                var name = $("input[name='name']").val();
                var email = $("input[name='email']").val();
                var vote = $("input[name='vote']:checked").val();
                $.ajax({
                    url: "ajax.php",
                    type: "POST",
                    data: {
                        name: name,
                        email: email,
                        vote: vote,
                        token: '<?php echo $token; ?>'
                    },
                    success: function(data) {
                        console.log(data);
                        var data = JSON.parse(data);
                        if (data.status == 'true') {
                            Toastify({
                                text: data.msg,
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                                className: "info",
                            }).showToast();
                            $("input[name='name']").val('');
                            $("input[name='email']").val('');
                            $("input[name='vote']").prop('checked', false);

                        } else {
                            Toastify({
                                text: data.msg,
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                className: "info",
                            }).showToast();
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>