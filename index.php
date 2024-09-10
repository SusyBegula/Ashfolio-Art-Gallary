<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./styles/index.css">
    <title>Ashfolio</title>
    <style>
        /* Fullscreen modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .model_class{
            display: grid;
            grid-template-columns: 0.8fr 1fr;
            grid-template-rows: 100%;
            height: 100%;
        }

        .left_div{
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .right_div{
            display: flex;
            height: 100%;
            justify-content: center;
            align-items: center;
            padding-left: 20px;
            color: #fff;
        }

        .right_info{
            text-align: justify;
            width: 80%;
        }

        .modal-content {
            display: block;
            margin: auto;
            max-width: 90%;
            max-height: 90%;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 25px;
            color: #fff;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
        }

        #modalImage{
            border-radius: 10px;
            height: 800px;
            box-shadow: 0px 0px 10px 0px white;
        }
    </style>
</head>
<body>
    <header>
        <div class="header_container">
            <h1>Ashfolio</h1>
            <div class="nav_main">
                <div class="navbar">
                    <span style="color: rgb(59, 144, 255);">Paintings</span>
                    <span>Illustrations</span>
                    <span>Fashion</span>
                    <span>Jewelry</span>
                </div>
            </div>
            <div class="link_div">
                <a href="https://www.instagram.com/ashromit17/" target="_blank"><img src="./styles/icons/insta.png" style="width: 30px;"></a>
                <a href="https://pin.it/4g32GfBMc" target="_blank"><img src="./styles/icons/pinterest.png" style="width: 30px;"></a>
                <a href="https://opensea.io/ashromit17" target="_blank"><img src="./styles/icons/opensea.png" style="width: 30px;"></a>
            </div>
        </div>
    </header>

    <div class="main_container">
        <div class="container">
            <?php
            // Load descriptions from JSON file
            $desc_file = 'descriptions.json';
            if (file_exists($desc_file)) {
                $descriptions = json_decode(file_get_contents($desc_file), true);
            } else {
                $descriptions = [];
            }

            // Load images from the 'uploads' folder
            $dir = './uploads/';
            $images = glob($dir . "*.{jpg,png,jpeg,gif}", GLOB_BRACE);

            // Display each image with its title and caption
            foreach ($images as $image) {
                $filename = basename($image);
                echo '<div class="img_container">';
                
                $title = $descriptions[$filename]['title'] ?? "Untitled";
                $caption = $descriptions[$filename]['caption'] ?? "No description available.";
                $short_caption = strlen($caption) > 250 ? substr($caption, 0, 250) . '...' : $caption;

                // Display image with title and short caption
                echo '<img src="' . $image . '" alt="Artwork" class="clickable_image" data-title="' . htmlspecialchars($title) . '" data-caption="' . htmlspecialchars(nl2br($caption)) . '">';

                echo '<div class="info">';
                echo '<h3>' . htmlspecialchars($title) . '</h3>'; // Display title
                echo '<p>' . nl2br(htmlspecialchars($short_caption)) . '</p>'; // Display short caption with newlines
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <!-- Fullscreen modal for displaying images -->
    <div id="myModal" class="modal">
        <div class="model_class">
            <div class="left_div">
                <span class="close">&times;</span>
                <img class="modal-content" id="modalImage">
            </div>
            <div class="right_div">
                <div class="right_info">
                    <h2 id="modalTitle"></h2>
                    <p id="modalCaption"></p>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <footer>
        <div class="main_foot">
            <div class="foot">
                <span>© 2024 Ashfolio</span>
                <span>Contact: <a href="mailto:ashromit3@gmail.com" style="color: rgb(59, 144, 255);">ashromit3@gmail.com</a></span>
                <span>Developed by <a href="https://github.com/SusyBegula">Susybegula</a></span>
            </div>
        </div>
    </footer>

    <script>
        // Get modal element
        var modal = document.getElementById("myModal");

        // Get the modal image element
        var modalImg = document.getElementById("modalImage");
        var modalTitle = document.getElementById("modalTitle");
        var modalCaption = document.getElementById("modalCaption");

        // Get the close button element
        var span = document.getElementsByClassName("close")[0];

        // Loop through all images with the class 'clickable_image' and add click event listeners
        var images = document.getElementsByClassName('clickable_image');
        for (var i = 0; i < images.length; i++) {
            images[i].onclick = function(){
                modal.style.display = "block";
                modalImg.src = this.src;
                modalTitle.textContent = this.getAttribute("data-title");
                modalCaption.innerHTML = this.getAttribute("data-caption"); // Use innerHTML to preserve <br> tags
            }
        }

        // Close the modal when the 'X' is clicked
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Close the modal when anywhere outside the image is clicked
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
