<?php
$base_directory = 'repository';
$current_directory = isset($_GET['dir']) ? $_GET['dir'] : '';

$directory = $base_directory . ($current_directory ? "/$current_directory" : '');

// Validate directory traversal
if (strpos(realpath($directory), realpath($base_directory)) !== 0) {
    die("<h1>Invalid directory!</h1>");
}

// Check if the directory exists
if (!is_dir($directory)) {
    die("<h1>Repository folder not found!</h1>");
}

// Get all files and directories
$items = array_diff(scandir($directory), array('.', '..'));

// Generate breadcrumbs
$breadcrumb_parts = explode('/', $current_directory);
$breadcrumb_links = [];
$path_accumulator = '';
foreach ($breadcrumb_parts as $part) {
    if ($part !== '') {
        $path_accumulator .= ($path_accumulator ? '/' : '') . $part;
        $breadcrumb_links[] = "<li><a href='?dir=$path_accumulator'>$part</a></li>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>KUO's Repository viewer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2b2d31;
            background-image: url('https://images7.alphacoders.com/838/thumb-1920-838143.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 70%;
            height: 70%;
            background: rgba(100, 30, 30, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 10px;
            border: 3px solid rgba(255,0,0, 0.4);
            padding: 20px;
            overflow-y: auto;
        }

        h1 {
            color: #ffffff;
            text-align: center;
        }

        ul {
            list-style-type: none;
            padding-left: 20px;
        }

        li {
            margin: 5px 0;
            position: relative;
        }

        a {
            text-decoration: none;
            color: white;
        }

        a:hover {
            text-decoration: none;
        }
        .element {
          transition: color 165ms linear;
        }
        
        .element:hover {
          color: rgba(255,0,0, 1);
        }


    </style>
    <script>
        function openPopup(url) {
            let ext = url.split('.').pop().toLowerCase();
            let viewerUrl = (ext === 'pdf' || ext === 'docx' || ext === 'ppt')
                ? `https://docs.google.com/gview?url=${window.location.origin}/${url}&embedded=true`
                : url;
            
            let popup = window.open("", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,fullscreen=yes");
            popup.document.write(`<iframe src="${viewerUrl}" style="width:100%; height:100vh; border:none;"></iframe>`);
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Repo Viewer <div class="badge badge-outline badge-success">updated<div class="inline-grid *:[grid-area:1/1]"> <div class="status status-success animate-ping"></div> <div class="status status-success"></div> </div></div></h1>
        
        <div class="breadcrumbs max-w-xs text-sm">
            <ul>
                <li><a href="?dir=">bin</a></li>
                <?= implode('', $breadcrumb_links) ?>
            </ul>
        </div>

        <ul>
            <?php foreach ($items as $item): ?>
                <?php $fullPath = "$directory/$item"; ?>
                <li>
                    <?php if (is_dir($fullPath)): ?>
                        <a href="?dir=<?= urlencode(trim("$current_directory/$item", '/')) ?>" class="folder element">📁 <?= htmlspecialchars($item) ?></a>
                    <?php else: ?>
                        <a href="#" onclick="openPopup('<?= htmlspecialchars($fullPath) ?>'); return false;" class="element">📄 <?= htmlspecialchars($item) ?></a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    
</body>
</html>
