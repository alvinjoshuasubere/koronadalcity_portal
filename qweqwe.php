<?php
$dataFile = __DIR__ . '/data/officials.json';
$imageDir = __DIR__ . '/static/officials/';

$officials = json_decode(file_get_contents($dataFile), true);
if (!is_array($officials)) {
    $officials = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $name = trim($_POST['name'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $ordinance = trim($_POST['ordinance'] ?? '');
        $committee = trim($_POST['committee'] ?? '');
        $imagePath = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp','svg'];
            if (in_array($ext, $allowed)) {
                $filename = uniqid('official_') . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $imageDir . $filename)) {
                    $imagePath = 'static/officials/' . $filename;
                }
            }
        }

        if ($action === 'add') {
            $newId = 0;
            foreach ($officials as $o) {
                if ($o['id'] > $newId) $newId = $o['id'];
            }
            $newId++;
            $officials[] = [
                'id' => $newId,
                'name' => $name,
                'position' => $position,
                'ordinance' => $ordinance,
                'committee' => $committee,
                'image' => $imagePath ?: 'static/officials/default.svg'
            ];
        } elseif ($action === 'edit') {
            $editId = (int)($_POST['id'] ?? 0);
            foreach ($officials as &$o) {
                if ($o['id'] === $editId) {
                    $o['name'] = $name;
                    $o['position'] = $position;
                    $o['ordinance'] = $ordinance;
                    $o['committee'] = $committee;
                    if ($imagePath) {
                        if (!empty($o['image']) && $o['image'] !== 'static/officials/default.svg') {
                            $oldFile = __DIR__ . '/' . $o['image'];
                            if (file_exists($oldFile)) unlink($oldFile);
                        }
                        $o['image'] = $imagePath;
                    }
                    break;
                }
            }
            unset($o);
        }

        file_put_contents($dataFile, json_encode($officials, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    if ($action === 'delete') {
        $delId = (int)($_POST['id'] ?? 0);
        foreach ($officials as $idx => $o) {
            if ($o['id'] === $delId) {
                if (!empty($o['image']) && $o['image'] !== 'static/officials/default.svg') {
                    $delFile = __DIR__ . '/' . $o['image'];
                    if (file_exists($delFile)) unlink($delFile);
                }
                unset($officials[$idx]);
                $officials = array_values($officials);
                break;
            }
        }
        file_put_contents($dataFile, json_encode($officials, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officials Directory Settings | Koronadal City</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box
    }

    body {
        font-family: 'Sora', sans-serif;
        background: #0B0E14;
        color: #e0e0e0;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .bg-orbs {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
        overflow: hidden
    }

    .bg-orbs .orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: .15
    }

    .bg-orbs .orb:nth-child(1) {
        width: 600px;
        height: 600px;
        background: #A83D5C;
        top: -200px;
        left: -100px;
        animation: floatOrb 20s ease-in-out infinite
    }

    .bg-orbs .orb:nth-child(2) {
        width: 500px;
        height: 500px;
        background: #4a1a3a;
        bottom: -150px;
        right: -100px;
        animation: floatOrb 25s ease-in-out infinite reverse
    }

    .bg-orbs .orb:nth-child(3) {
        width: 400px;
        height: 400px;
        background: #A83D5C;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation: floatOrb 30s ease-in-out infinite
    }

    @keyframes floatOrb {

        0%,
        100% {
            transform: translate(0, 0)
        }

        25% {
            transform: translate(30px, -40px)
        }

        50% {
            transform: translate(-20px, 30px)
        }

        75% {
            transform: translate(40px, 20px)
        }
    }

    nav {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 100;
        background: rgba(11, 14, 20, .85);
        backdrop-filter: blur(24px);
        border-bottom: 1px solid rgba(255, 255, 255, .06);
        padding: 0 2rem;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .nav-brand {
        display: flex;
        align-items: center;
        gap: .75rem;
        text-decoration: none
    }

    .nav-brand .logo {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #A83D5C, #7a2840);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: .85rem;
        color: #fff
    }

    .nav-brand span {
        font-weight: 600;
        color: #fff;
        font-size: .95rem
    }

    .nav-center {
        color: rgba(255, 255, 255, .7);
        font-size: .85rem;
        font-weight: 400;
        position: absolute;
        left: 50%;
        transform: translateX(-50%)
    }

    .nav-right {
        display: flex;
        align-items: center;
        gap: 1rem
    }

    .nav-right a {
        color: rgba(255, 255, 255, .6);
        text-decoration: none;
        font-size: .82rem;
        transition: color .2s;
        display: flex;
        align-items: center;
        gap: .4rem;
    }

    .nav-right a:hover {
        color: #A83D5C
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 80px 2rem 3rem;
        position: relative;
        z-index: 1
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem
    }

    .page-header h1 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #fff
    }

    .page-header h1 i {
        color: #A83D5C;
        margin-right: .5rem
    }

    .brand-img {
        width: 32px;
        height: 32px;
    }

    .btn {
        padding: .55rem 1.2rem;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-family: 'Sora', sans-serif;
        font-size: .8rem;
        font-weight: 500;
        transition: all .25s;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }

    .btn-rose {
        background: linear-gradient(135deg, #A83D5C, #7a2840);
        color: #fff;
        box-shadow: 0 4px 20px rgba(168, 61, 92, .3);
    }

    .btn-rose:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 28px rgba(168, 61, 92, .5)
    }

    .btn-ghost {
        background: rgba(255, 255, 255, .06);
        color: rgba(255, 255, 255, .7);
        border: 1px solid rgba(255, 255, 255, .08);
    }

    .btn-ghost:hover {
        background: rgba(255, 255, 255, .1);
        color: #fff
    }

    .btn-danger {
        background: rgba(220, 50, 50, .15);
        color: #ff6b6b;
        border: 1px solid rgba(220, 50, 50, .2);
    }

    .btn-danger:hover {
        background: rgba(220, 50, 50, .25)
    }

    .stats-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .stat-card {
        background: rgba(22, 27, 38, .8);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, .06);
        border-radius: 14px;
        padding: 1rem 1.5rem;
        flex: 1;
        min-width: 180px;
    }

    .stat-card .stat-label {
        font-size: .72rem;
        color: rgba(255, 255, 255, .45);
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: .3rem
    }

    .stat-card .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #A83D5C
    }

    .stat-card .stat-sub {
        font-size: .75rem;
        color: rgba(255, 255, 255, .35);
        margin-top: .15rem
    }

    .officials-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.2rem;
    }

    .official-card {
        background: rgba(22, 27, 38, .8);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, .06);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all .3s ease;
        position: relative;
        overflow: hidden;
    }

    .official-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, #A83D5C, transparent);
        opacity: 0;
        transition: opacity .3s;
    }

    .official-card:hover {
        border-color: rgba(168, 61, 92, .3);
        transform: translateY(-4px);
        box-shadow: 0 8px 40px rgba(168, 61, 92, .12), 0 0 0 1px rgba(168, 61, 92, .1);
    }

    .official-card:hover::before {
        opacity: 1
    }

    .official-photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto .8rem;
        border: 3px solid rgba(168, 61, 92, .4);
        box-shadow: 0 0 15px rgba(168, 61, 92, .4), 0 0 30px rgba(168, 61, 92, .15);
        display: block;
        background: rgba(168, 61, 92, .1);
    }

    .official-card h3 {
        font-size: .95rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: .2rem
    }

    .official-card .position {
        font-size: .78rem;
        color: #A83D5C;
        font-weight: 500;
        margin-bottom: .5rem
    }

    .official-card .ordinance {
        font-size: .75rem;
        color: rgba(255, 255, 255, .5);
        line-height: 1.45;
        margin-bottom: .6rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden
    }

    .committee-tags {
        display: flex;
        flex-wrap: wrap;
        gap: .3rem;
        justify-content: center;
        margin-bottom: .8rem
    }

    .committee-tag {
        background: rgba(168, 61, 92, .15);
        color: rgba(168, 61, 92, .9);
        padding: .15rem .55rem;
        border-radius: 20px;
        font-size: .65rem;
        font-weight: 500;
        border: 1px solid rgba(168, 61, 92, .2);
    }

    .card-actions {
        display: flex;
        gap: .5rem;
        justify-content: center;
        margin-top: .3rem
    }

    .card-actions .btn {
        padding: .4rem .9rem;
        font-size: .72rem
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 200;
        background: rgba(0, 0, 0, .6);
        backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity .3s;
    }

    .modal-overlay.active {
        display: flex;
        opacity: 1
    }

    .modal {
        background: rgba(22, 27, 38, .95);
        backdrop-filter: blur(30px);
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 20px;
        width: 90%;
        max-width: 520px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 2rem;
        transform: scale(.92) translateY(20px);
        transition: transform .3s ease;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .5), 0 0 40px rgba(168, 61, 92, .08);
    }

    .modal-overlay.active .modal {
        transform: scale(1) translateY(0)
    }

    .modal h2 {
        font-size: 1.15rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 1.2rem
    }

    .modal h2 i {
        color: #A83D5C;
        margin-right: .4rem
    }

    .form-group {
        margin-bottom: 1rem
    }

    .form-group label {
        display: block;
        font-size: .75rem;
        font-weight: 500;
        color: rgba(255, 255, 255, .6);
        margin-bottom: .35rem
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: .65rem .9rem;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, .08);
        background: rgba(255, 255, 255, .04);
        color: #fff;
        font-family: 'Sora', sans-serif;
        font-size: .85rem;
        transition: border-color .2s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: rgba(168, 61, 92, .5)
    }

    .form-group textarea {
        resize: vertical;
        min-height: 80px
    }

    .image-upload-area {
        border: 2px dashed rgba(255, 255, 255, .1);
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all .2s;
        position: relative;
    }

    .image-upload-area:hover {
        border-color: rgba(168, 61, 92, .4)
    }

    .image-upload-area input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .image-upload-area i {
        font-size: 1.5rem;
        color: rgba(255, 255, 255, .2);
        margin-bottom: .3rem;
        display: block
    }

    .image-upload-area span {
        font-size: .75rem;
        color: rgba(255, 255, 255, .35)
    }

    .tag-input-wrap {
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 10px;
        background: rgba(255, 255, 255, .04);
        padding: .5rem;
        min-height: 44px;
    }

    .tag-input-wrap:focus-within {
        border-color: rgba(168, 61, 92, .5)
    }

    .tag-input-tags {
        display: flex;
        flex-wrap: wrap;
        gap: .3rem;
        margin-bottom: .4rem
    }

    .tag-input-tags:empty {
        display: none;
        margin-bottom: 0
    }

    .tag-input-item {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(168, 61, 92, .2);
        color: rgba(255, 255, 255, .85);
        padding: .2rem .6rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 500;
        border: 1px solid rgba(168, 61, 92, .3);
    }

    .tag-input-item button {
        background: none;
        border: none;
        color: rgba(255, 255, 255, .4);
        cursor: pointer;
        font-size: .7rem;
        padding: 0 2px;
        line-height: 1;
        transition: color .2s;
    }

    .tag-input-item button:hover {
        color: #ff6b6b
    }

    .tag-input-row {
        display: flex;
        gap: .4rem
    }

    .tag-input-row input {
        flex: 1;
        padding: .4rem .6rem;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, .06);
        background: rgba(255, 255, 255, .04);
        color: #fff;
        font-family: 'Sora', sans-serif;
        font-size: .8rem;
    }

    .tag-input-row input:focus {
        outline: none
    }

    .tag-add-btn {
        padding: .4rem .8rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        background: rgba(168, 61, 92, .2);
        color: rgba(255, 255, 255, .7);
        font-family: 'Sora', sans-serif;
        font-size: .75rem;
        font-weight: 500;
        transition: all .2s;
        white-space: nowrap;
    }

    .tag-add-btn:hover {
        background: rgba(168, 61, 92, .4);
        color: #fff
    }

    .image-preview {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(168, 61, 92, .3);
        margin: 0 auto .5rem;
        display: block;
    }

    .modal-actions {
        display: flex;
        gap: .7rem;
        justify-content: flex-end;
        margin-top: 1.5rem
    }

    @media(max-width:900px) {
        .officials-grid {
            grid-template-columns: repeat(3, 1fr)
        }

        .official-photo {
            width: 80px;
            height: 80px
        }

        .official-card {
            padding: 1rem
        }

        .official-card h3 {
            font-size: .82rem
        }

        .official-card .position {
            font-size: .7rem
        }

        .official-card .ordinance {
            font-size: .68rem
        }

        .committee-tag {
            font-size: .58rem;
            padding: .1rem .45rem
        }

        .card-actions .btn {
            padding: .3rem .7rem;
            font-size: .65rem
        }

        .stat-card {
            padding: .8rem 1rem
        }

        .stat-card .stat-value {
            font-size: 1.4rem
        }
    }

    @media(max-width:600px) {
        .officials-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: .6rem
        }

        .container {
            padding: 70px .8rem 2rem
        }

        nav {
            padding: 0 .8rem
        }

        .nav-brand span {
            font-size: .78rem
        }

        .page-header h1 {
            font-size: 1.1rem
        }

        .page-header h1 i {
            display: none
        }

        .btn {
            padding: .45rem .9rem;
            font-size: .72rem
        }

        .official-photo {
            width: 60px;
            height: 60px;
            border-width: 2px
        }

        .official-card {
            padding: .7rem .5rem;
            border-radius: 12px
        }

        .official-card h3 {
            font-size: .68rem
        }

        .official-card .position {
            font-size: .6rem;
            margin-bottom: .3rem
        }

        .official-card .ordinance {
            font-size: .58rem;
            -webkit-line-clamp: 1
        }

        .committee-tags {
            gap: .2rem;
            margin-bottom: .5rem
        }

        .committee-tag {
            font-size: .5rem;
            padding: .05rem .35rem
        }

        .card-actions {
            margin-top: .2rem
        }

        .card-actions .btn {
            padding: .25rem .5rem;
            font-size: .58rem;
            gap: .2rem
        }

        .card-actions .btn i {
            font-size: .55rem
        }

        .stats-bar {
            gap: .5rem
        }

        .stat-card {
            padding: .6rem .8rem
        }

        .stat-card .stat-value {
            font-size: 1.2rem
        }

        .stat-card .stat-label {
            font-size: .62rem
        }

        .stat-card .stat-sub {
            font-size: .65rem
        }

        .nav-center {
            font-size: .72rem
        }

        .modal {
            width: 95%;
            padding: 1.2rem;
            border-radius: 14px
        }

        .modal h2 {
            font-size: 1rem
        }
    }

    .empty-state {
        grid-column: 1/-1;
        text-align: center;
        padding: 3rem 1rem;
        color: rgba(255, 255, 255, .3);
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: .8rem;
        color: rgba(168, 61, 92, .3);
        display: block
    }
    </style>
</head>

<body>
    <div class="bg-orbs">
        <div class="orb"></div>
        <div class="orb"></div>
        <div class="orb"></div>
    </div>

    <nav>
        <a href="/" class="nav-brand">
            <img src="Logo.png" alt="Koronadal City" class="brand-img" />
            <span>City of Koronadal</span>
        </a>
        <div class="nav-center">Officials Directory Settings</div>
        <div class="nav-right">
            <a href="/"><i class="fa-solid fa-arrow-left"></i> Back to Portal</a>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1><i class="fa-solid fa-landmark"></i> Officials Directory Manager</h1>
            <button class="btn btn-rose" onclick="openModal()"><i class="fa-solid fa-plus"></i> Add New
                Official</button>
        </div>

        <div class="stats-bar">
            <div class="stat-card">
                <div class="stat-label">Total Officials</div>
                <div class="stat-value"><?= count($officials) ?></div>
                <div class="stat-sub">Registered in directory</div>
            </div>
        </div>

        <div class="officials-grid">
            <?php if (empty($officials)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-user-slash"></i>
                <p>No officials found. Add your first official to get started.</p>
            </div>
            <?php else: ?>
            <?php foreach ($officials as $official): ?>
            <div class="official-card">
                <img class="official-photo" src="<?= htmlspecialchars($official['image']) ?>"
                    alt="<?= htmlspecialchars($official['name']) ?>">
                <h3><?= htmlspecialchars($official['name']) ?></h3>
                <div class="position"><?= htmlspecialchars($official['position']) ?></div>
                <div class="ordinance"><?= htmlspecialchars($official['ordinance']) ?></div>
                <div class="committee-tags">
                    <?php
                            $tags = array_map('trim', explode(',', $official['committee']));
                            foreach ($tags as $tag):
                                if ($tag === '') continue;
                            ?>
                    <span class="committee-tag"><?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="card-actions">
                    <button class="btn btn-ghost"
                        onclick='openEditModal(<?= json_encode($official, JSON_HEX_APOS | JSON_HEX_TAG) ?>)'><i
                            class="fa-solid fa-pen"></i> Edit</button>
                    <button class="btn btn-danger"
                        onclick="confirmDelete(<?= $official['id'] ?>, '<?= htmlspecialchars(addslashes($official['name'])) ?>')"><i
                            class="fa-solid fa-trash"></i> Delete</button>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal-overlay" id="officialModal">
        <div class="modal">
            <h2 id="modalTitle"><i class="fa-solid fa-user-plus"></i> Add Official</h2>
            <form id="officialForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="formId" value="">

                <div id="currentImageWrap" style="display:none;text-align:center;margin-bottom:.8rem">
                    <img id="currentImage" class="image-preview" src="" alt="Current">
                    <div style="font-size:.7rem;color:rgba(255,255,255,.35)">Current photo</div>
                </div>

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="formName" required placeholder="e.g. Hon. Juan Dela Cruz">
                </div>
                <div class="form-group">
                    <label>Position</label>
                    <input type="text" name="position" id="formPosition" required placeholder="e.g. City Mayor">
                </div>
                <div class="form-group">
                    <label>Ordinance</label>
                    <textarea name="ordinance" id="formOrdinance"
                        placeholder="Description of role or ordinance"></textarea>
                </div>
                <div class="form-group">
                    <label>Committee (press Enter or click Add to insert multiple)</label>
                    <div class="tag-input-wrap" id="tagInputWrap">
                        <div class="tag-input-tags" id="tagInputTags"></div>
                        <div class="tag-input-row">
                            <input type="text" id="tagInputField" placeholder="Type committee name...">
                            <button type="button" class="tag-add-btn" onclick="addTag()"><i
                                    class="fa-solid fa-plus"></i> Add</button>
                        </div>
                    </div>
                    <input type="hidden" name="committee" id="formCommittee">
                </div>
                <div class="form-group">
                    <label>Photo</label>
                    <div class="image-upload-area" id="uploadArea">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Click or drag to upload photo</span>
                        <input type="file" name="image" id="formImage" accept="image/*">
                    </div>
                    <img id="imagePreview" class="image-preview" src="" alt="Preview"
                        style="display:none;margin-top:.5rem">
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-ghost" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-rose" id="formSubmitBtn"><i class="fa-solid fa-check"></i> Save
                        Official</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="deleteModal">
        <div class="modal" style="max-width:400px;text-align:center">
            <i class="fa-solid fa-triangle-exclamation"
                style="font-size:2rem;color:#ff6b6b;margin-bottom:.5rem;display:block"></i>
            <h2 style="justify-content:center">Delete Official?</h2>
            <p style="font-size:.85rem;color:rgba(255,255,255,.5);margin-bottom:1.2rem">
                Are you sure you want to remove <strong id="deleteName" style="color:#fff"></strong> from the directory?
                This cannot be undone.
            </p>
            <form method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteId" value="">
                <div class="modal-actions" style="justify-content:center">
                    <button type="button" class="btn btn-ghost" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    var currentTags = [];

    function renderTags() {
        var container = document.getElementById('tagInputTags');
        container.innerHTML = '';
        currentTags.forEach(function(tag, i) {
            var el = document.createElement('span');
            el.className = 'tag-input-item';
            el.innerHTML = tag + ' <button type="button" onclick="removeTag(' + i + ')">&times;</button>';
            container.appendChild(el);
        });
        document.getElementById('formCommittee').value = currentTags.join(',');
    }

    function addTag() {
        var field = document.getElementById('tagInputField');
        var val = field.value.trim();
        if (val && currentTags.indexOf(val) === -1) {
            currentTags.push(val);
            renderTags();
        }
        field.value = '';
        field.focus();
    }

    function removeTag(i) {
        currentTags.splice(i, 1);
        renderTags();
    }

    document.getElementById('tagInputField').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addTag();
        }
    });

    function openModal() {
        document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-user-plus"></i> Add Official';
        document.getElementById('formAction').value = 'add';
        document.getElementById('formId').value = '';
        document.getElementById('formName').value = '';
        document.getElementById('formPosition').value = '';
        document.getElementById('formOrdinance').value = '';
        currentTags = [];
        renderTags();
        document.getElementById('formImage').value = '';
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('currentImageWrap').style.display = 'none';
        document.getElementById('formSubmitBtn').innerHTML = '<i class="fa-solid fa-check"></i> Save Official';
        showModal('officialModal');
    }

    function openEditModal(official) {
        document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-user-pen"></i> Edit Official';
        document.getElementById('formAction').value = 'edit';
        document.getElementById('formId').value = official.id;
        document.getElementById('formName').value = official.name;
        document.getElementById('formPosition').value = official.position;
        document.getElementById('formOrdinance').value = official.ordinance;
        currentTags = official.committee ? official.committee.split(',').map(function(s) {
            return s.trim();
        }).filter(function(s) {
            return s !== '';
        }) : [];
        renderTags();
        document.getElementById('formImage').value = '';
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('currentImageWrap').style.display = 'block';
        document.getElementById('currentImage').src = official.image;
        document.getElementById('formSubmitBtn').innerHTML = '<i class="fa-solid fa-check"></i> Update Official';
        showModal('officialModal');
    }

    function confirmDelete(id, name) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteName').textContent = name;
        showModal('deleteModal');
    }

    function showModal(id) {
        var m = document.getElementById(id);
        m.style.display = 'flex';
        requestAnimationFrame(function() {
            m.classList.add('active');
        });
    }

    function closeModal() {
        var m = document.getElementById('officialModal');
        m.classList.remove('active');
        setTimeout(function() {
            m.style.display = 'none';
        }, 300);
    }

    function closeDeleteModal() {
        var m = document.getElementById('deleteModal');
        m.classList.remove('active');
        setTimeout(function() {
            m.style.display = 'none';
        }, 300);
    }

    document.querySelectorAll('.modal-overlay').forEach(function(m) {
        m.addEventListener('click', function(e) {
            if (e.target === m) {
                m.classList.remove('active');
                setTimeout(function() {
                    m.style.display = 'none';
                }, 300);
            }
        });
    });

    document.getElementById('formImage').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(ev) {
                var preview = document.getElementById('imagePreview');
                preview.src = ev.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    </script>
</body>

</html>