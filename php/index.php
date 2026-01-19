<?php
// pfade
$relativeDir = 'hoerspiele'; 
$scanDir = __DIR__ . '/' . $relativeDir;

// ordner scannen
$folders = array_filter(glob($scanDir . '/*', GLOB_ONLYDIR), function($dir) {
    return !in_array(basename($dir), ['.', '..']);
});

$artists = [];
foreach ($folders as $folderPath) {
    $folderName = basename($folderPath);
    $nameFile = $folderPath . '/name.txt';
    $artistName = file_exists($nameFile) ? trim(file_get_contents($nameFile)) : ucfirst($folderName);
    $images = glob($folderPath . '/*.png');
    $imagePath = !empty($images) ? $relativeDir . '/' . $folderName . '/' . basename($images[0]) : null;

    $artists[] = [
        'name' => htmlspecialchars($artistName),
        'image' => $imagePath,
        'link' => $relativeDir . '/' . $folderName . '/index.html'
    ];
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HörspielArchiv - by pidi</title>
    <style>
        :root {
            --bg: #121212;
            --card: #181818;
            --hover: #282828;
            --text: #ffffff;
            --text-muted: #b3b3b3;
            --search-bg: #242424;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px 40px;
        }

        /* search bar styling */
        .search-container {
            margin-bottom: 30px;
            display: flex;
            justify-content: flex-start;
        }

        #searchBar {
            width: 100%;
            padding: 12px 20px;
            border-radius: 500px; /* spotify style pill */
            border: none;
            background: var(--search-bg);
            color: white;
            font-size: 14px;
            outline: none;
            transition: background 0.2s;
        }

        #searchBar:focus {
            background: #2a2a2a;
            box-shadow: 0 0 0 1px #fff;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 24px;
        }

        .card {
            background: var(--card);
            padding: 16px;
            border-radius: 8px;
            text-decoration: none;
            color: inherit;
            transition: background 0.3s ease;
        }

        /* verstecken wenn gefiltert */
        .card.hidden {
            display: none;
        }

        .card:hover {
            background: var(--hover);
        }

        .img-container {
            width: 100%;
            aspect-ratio: 1;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.5);
        }

        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .name {
            font-weight: 700;
            font-size: 16px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .type {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 4px;
        }
    </style>
</head>
<body>

    <div class="search-container">
        <input type="text" id="searchBar" placeholder="Was möchtest du hören?" onkeyup="filterArtists()">
    </div>

    <div class="grid" id="artistGrid">
        <?php foreach ($artists as $artist): ?>
            <a href="<?= $artist['link'] ?>" class="card" data-name="<?= strtolower($artist['name']) ?>">
                <div class="img-container">
                    <?php if ($artist['image']): ?>
                        <img src="<?= $artist['image'] ?>" alt="">
                    <?php else: ?>
                        <div style="width:100%; height:100%; background:#333; display:flex; align-items:center; justify-content:center;">?</div>
                    <?php endif; ?>
                </div>
                <div class="name"><?= $artist['name'] ?></div>
                <div class="type">Hörspiel/Hörbuch</div>
            </a>
        <?php endforeach; ?>
    </div>

    <script>
    function filterArtists() {
        let input = document.getElementById('searchBar').value.toLowerCase();
        let cards = document.querySelectorAll('.card');
        
        cards.forEach(card => {
            let name = card.getAttribute('data-name');
            if (name.includes(input)) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }
    </script>

</body>
</html>
