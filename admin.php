<?php
session_start();

// Proste hasło do panelu administracyjnego
$password = "garaz2026";

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $password) {
        $_SESSION['logged_in'] = true;
    } else {
        $error = "Błędne hasło";
    }
}

if (!isset($_SESSION['logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Garaż - Logowanie do panelu</title>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    </head>
    <body style="background:#0c0c0c; color:#fff; font-family:'DM Sans', sans-serif; display:flex; justify-content:center; align-items:center; height:100vh; margin:0;">
      <form method="POST" style="background:#1a1a1a; padding:40px; border-radius:12px; width:100%; max-width:320px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <h2 style="margin-top:0; text-align:center; color:#f1c40f;">Panel Wydarzeń</h2>
        <?php if(isset($error)) echo "<p style='color:#e74c3c; text-align:center;'>$error</p>"; ?>
        <input type="password" name="password" placeholder="Wpisz hasło" style="padding:12px; width:100%; margin-bottom:20px; background:#2a2a2a; border:1px solid #333; color:#fff; border-radius:6px; box-sizing:border-box;">
        <button type="submit" style="padding:12px; width:100%; background:#f1c40f; color:#000; border:none; border-radius:6px; font-weight:bold; cursor:pointer; font-size:16px;">Zaloguj się</button>
      </form>
    </body>
    </html>
    <?php
    exit;
}

// Obsługa zapisu/usuwania wydarzenia
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'save') {
        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'end_time' => $_POST['end_time'] ?? ''
        ];
        file_put_contents('event.json', json_encode($data));
        $msg = "Wydarzenie zostało pomyślnie zapisane i opublikowane na stronie!";
    } elseif ($_POST['action'] === 'clear') {
        $data = [
            'title' => '',
            'description' => '',
            'end_time' => ''
        ];
        file_put_contents('event.json', json_encode($data));
        $msg = "Wydarzenie zostało usunięte ze strony głównej!";
    }
}

$current_event = ['title' => '', 'description' => '', 'end_time' => ''];
if (file_exists('event.json')) {
    $current_event = json_decode(file_get_contents('event.json'), true) ?: $current_event;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzaj Wydarzeniem - Garaż</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { background: #0c0c0c; color: #f4f4f4; font-family: 'DM Sans', sans-serif; padding: 20px; margin: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #1a1a1a; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        h2 { margin-top: 0; color: #f1c40f; }
        label { display: block; margin-bottom: 8px; color: #ccc; font-size: 0.9rem; }
        input, textarea { width: 100%; padding: 12px; margin-bottom: 24px; background: #2a2a2a; border: 1px solid #333; color: #fff; border-radius: 6px; box-sizing: border-box; font-family: 'DM Sans', sans-serif;}
        input:focus, textarea:focus { border-color: #f1c40f; outline: none; }
        button { padding: 14px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 1rem; width: 100%; transition: opacity 0.2s;}
        button:hover { opacity: 0.9; }
        .btn-save { background: #f1c40f; color: #0c0c0c; font-weight: bold; }
        .btn-clear { background: #e74c3c; color: #fff; margin-top: 10px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #333; padding-bottom: 20px; }
        .header a { color: #f1c40f; text-decoration: none; font-size: 0.9rem; border: 1px solid #f1c40f; padding: 6px 12px; border-radius: 4px; }
        .msg { background: rgba(46, 204, 113, 0.1); color: #2ecc71; padding: 12px; border-radius: 6px; margin-bottom: 24px; border-left: 4px solid #2ecc71; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0;">Aktywne Wydarzenie</h2>
            <a href="?logout=1">Wyloguj</a>
        </div>
        
        <?php if(isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

        <form method="POST">
            <input type="hidden" name="action" value="save">
            
            <label>Nazwa wydarzenia lub promocji</label>
            <input type="text" name="title" value="<?= htmlspecialchars($current_event['title']) ?>" placeholder="np. Weekend z muzyką na żywo!" required>
            
            <label>Krótki opis</label>
            <textarea name="description" rows="3" placeholder="np. Wpadnijcie na pyszne pastrami i darmowe przekąski barowe." required><?= htmlspecialchars($current_event['description']) ?></textarea>
            
            <label>Czas trwania (np. data końcowa, opcjonalnie)</label>
            <input type="text" name="end_time" value="<?= htmlspecialchars($current_event['end_time']) ?>" placeholder="np. Obowiązuje do końca weekendu">
            
            <button type="submit" class="btn-save">Opublikuj na stronie</button>
        </form>

        <form method="POST" onsubmit="return confirm('Na pewno chcesz usunąć widoczne wydarzenie z głównej strony?');">
            <input type="hidden" name="action" value="clear">
            <button type="submit" class="btn-clear">Zakończ wydarzenie (Ukryj okienko)</button>
        </form>
    </div>
</body>
</html>
