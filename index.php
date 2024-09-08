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
                echo '<img src="' . $image . '" alt="Artwork">';

                // Display title and caption if available
                if (isset($descriptions[$filename])) {
                    $title = $descriptions[$filename]['title'];
                    $caption = $descriptions[$filename]['caption'];
                } else {
                    $title = "Untitled";
                    $caption = "No description available.";
                }

                echo '<div class="info">';
                echo '<h3>' . htmlspecialchars($title) . '</h3>'; // Display title
                echo '<p>' . htmlspecialchars($caption) . '</p>'; // Display caption
                echo '</div>';
                echo '</div>';
            }
            ?>
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
</body>
</html>
