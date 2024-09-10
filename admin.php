<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/admin.css">
    <title>Admin Panel - Ashfolio</title>
</head>
<body>
    <h1>Ashfolio Admin Panel</h1>

    <section>
        <!-- Image Upload Form -->
        <form id="uploadForm" action="admin.php" method="post" enctype="multipart/form-data">
            <label for="fileInput">Choose image to upload:</label>
            <input type="file" id="fileInput" name="image" accept="image/*" onchange="previewImage()">
            
            <div id="previewContainer" style="display: none;">
                <img id="imagePreview" src="" alt="Image Preview">
            </div>

            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            
            <label for="caption">Caption:</label>
            <textarea id="caption" name="caption" rows="4" cols="50" placeholder="Enter your caption here..." required></textarea><br>
            
            <input type="submit" name="upload" value="Upload Image">
        </form>
    </section>

    <?php
    $target_dir = "uploads/";
    $desc_file = 'descriptions.json';

    // Initialize descriptions if file does not exist
    if (!file_exists($desc_file)) {
        file_put_contents($desc_file, json_encode([]));
    }

    // Load existing descriptions
    $descriptions = json_decode(file_get_contents($desc_file), true);

    // Handle Image Upload
    if (isset($_POST['upload'])) {
        if (!isset($_FILES["image"])) {
            echo "No file uploaded.<br>";
        } else {
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a real image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    echo "The file " . basename($_FILES["image"]["name"]) . " has been uploaded.<br>";
                    
                    // Save the title and description
                    $title = $_POST['title'];
                    $caption = $_POST['caption'];
                    $descriptions[basename($_FILES["image"]["name"])] = [
                        'title' => $title,
                        'caption' => $caption
                    ];
                    file_put_contents($desc_file, json_encode($descriptions, JSON_PRETTY_PRINT));
                } else {
                    echo "Sorry, there was an error uploading your file.<br>";
                }
            } else {
                echo "File is not an image.<br>";
            }
        }
    }

    // Handle Image Replacement
    if (isset($_POST['replace'])) {
        if (!isset($_FILES['replace_image'])) {
            echo "No file selected for replacement.<br>";
        } else {
            $filename = $_POST['filename'];
            $replace_file = $_FILES['replace_image']['tmp_name'];
            $target_file = $target_dir . basename($filename);

            if (file_exists($target_file)) {
                if (move_uploaded_file($replace_file, $target_file)) {
                    echo "The file " . basename($filename) . " has been replaced.<br>";
                } else {
                    echo "Sorry, there was an error replacing your file.<br>";
                }
            } else {
                echo "File does not exist.<br>";
            }
        }
    }

    // Handle Image Deletion
    if (isset($_POST['delete'])) {
        $filename = $_POST['filename'];
        $file_path = $target_dir . $filename;
        if (file_exists($file_path)) {
            unlink($file_path);
            unset($descriptions[$filename]); // Remove description
            file_put_contents($desc_file, json_encode($descriptions, JSON_PRETTY_PRINT)); // Update description file
            echo "File " . $filename . " deleted successfully.<br>";
        } else {
            echo "File does not exist.<br>";
        }
    }

    // Handle Title and Caption Edit
    if (isset($_POST['edit'])) {
        $filename = $_POST['filename'];
        $new_title = $_POST['new_title'];
        $new_caption = $_POST['new_caption'];
        if (isset($descriptions[$filename])) {
            $descriptions[$filename] = [
                'title' => $new_title,
                'caption' => $new_caption
            ];
            file_put_contents($desc_file, json_encode($descriptions, JSON_PRETTY_PRINT));
            echo "Details for " . $filename . " updated.<br>";
        } else {
            echo "No details found for the file.<br>";
        }
    }
    ?>

    <div class="gallery">
        <?php
        // Load images from the 'uploads' folder
        $images = glob($target_dir . "*.{jpg,png,jpeg,gif}", GLOB_BRACE);

        foreach ($images as $image) {
            $filename = basename($image);
            echo '<div class="img_container">';
            echo '<img src="' . $image . '" alt="Artwork" style="width: 300px; height: auto;">';
            
            // Edit Title and Caption Form
            echo '<form method="POST" action="admin.php">';
            echo '<input type="hidden" name="filename" value="' . $filename . '">';
            echo '<input type="text" name="new_title" placeholder="Edit title..." value="' . (isset($descriptions[$filename]['title']) ? $descriptions[$filename]['title'] : '') . '">';
            echo '<textarea name="new_caption" rows="4" cols="50" placeholder="Edit caption...">' . (isset($descriptions[$filename]['caption']) ? $descriptions[$filename]['caption'] : '') . '</textarea>';
            echo '<input type="submit" name="edit" value="Edit">';
            echo '</form>';

            // Replace Image Form
            echo '<form method="POST" action="admin.php" enctype="multipart/form-data">';
            echo '<input type="hidden" name="filename" value="' . $filename . '">';
            echo '<label for="replace_image">Replace image:</label>';
            echo '<input type="file" name="replace_image" id="replace_image">';
            echo '<input type="submit" name="replace" value="Replace">';
            echo '</form>';

            // Delete Image Form
            echo '<form method="POST" action="admin.php">';
            echo '<input type="hidden" name="filename" value="' . $filename . '">';
            echo '<input type="submit" name="delete" value="Delete">';
            echo '</form>';

            echo '</div>';
        }
        ?>
    </div>

    <script>
        function previewImage() {
            const fileInput = document.getElementById('fileInput');
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                previewContainer.style.display = 'none';
            }
        }
    </script>
</body>
</html>
