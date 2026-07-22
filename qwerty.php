<?php
$dataFile = __DIR__ . '/data/emergency_contacts.json';
$contacts = [];
if (file_exists($dataFile)) {
    $json = file_get_contents($dataFile);
    $data = json_decode($json, true);
    if (is_array($data)) {
        $contacts = $data;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $newId = 0;
        foreach ($contacts as $c) {
            if ($c['id'] > $newId) $newId = $c['id'];
        }
        $newId++;
        $contacts[] = [
            'id'          => $newId,
            'name'        => trim($_POST['name'] ?? ''),
            'type'        => trim($_POST['type'] ?? 'general'),
            'phone'       => trim($_POST['phone'] ?? ''),
            'description' => trim($_POST['desc'] ?? ''),
        ];
    } elseif ($action === 'edit') {
        $eid = intval($_POST['id'] ?? 0);
        foreach ($contacts as $i => $c) {
            if ($c['id'] === $eid) {
                $contacts[$i]['name']        = trim($_POST['name'] ?? '');
                $contacts[$i]['type']        = trim($_POST['type'] ?? 'general');
                $contacts[$i]['phone']       = trim($_POST['phone'] ?? '');
                $contacts[$i]['description'] = trim($_POST['desc'] ?? '');
                break;
            }
        }
    } elseif ($action === 'delete') {
        $did = intval($_POST['id'] ?? 0);
        foreach ($contacts as $i => $c) {
            if ($c['id'] === $did) {
                array_splice($contacts, $i, 1);
                break;
            }
        }
    }

    file_put_contents($dataFile, json_encode($contacts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Emergency Contacts – Koronadal City Admin</title>
<link rel="icon" type="image/x-icon" href="favicon.ico" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{font-family:"Sora",system-ui,sans-serif;background:#0B0E14;color:#E2E4EA;-webkit-font-smoothing:antialiased;font-size:14px;overflow-x:hidden;line-height:1.5}
body::before{content:'';position:fixed;top:-30%;left:-20%;width:80%;height:80%;background:radial-gradient(circle,rgba(168,61,92,0.12),transparent 70%);pointer-events:none;z-index:0}
body::after{content:'';position:fixed;bottom:-30%;right:-20%;width:80%;height:80%;background:radial-gradient(circle,rgba(168,61,92,0.06),transparent 70%);pointer-events:none;z-index:0}
a{color:inherit;text-decoration:none}
button{font-family:inherit;cursor:pointer}
::selection{background:#A83D5C;color:#fff}

.nav{position:fixed;top:0;left:0;right:0;z-index:100;height:56px;background:rgba(13,16,22,0.9);border-bottom:1px solid rgba(255,255,255,0.06);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px)}
.nav-in{max-width:1200px;margin:0 auto;padding:0 16px;height:100%;display:flex;align-items:center;justify-content:space-between;position:relative;z-index:1}
.nav-brand{display:flex;align-items:center;gap:8px}
.nav-brand i{font-size:1rem;color:#A83D5C}
.nav-brand strong{font-size:0.9rem;font-weight:700;color:#E2E4EA}
.nav-brand small{font-size:0.6rem;font-weight:600;color:#A83D5C;display:block;line-height:1;margin-top:0}
.nav-r{display:flex;align-items:center;gap:12px}
.nav-back{display:inline-flex;align-items:center;gap:6px;font-size:0.72rem;font-weight:600;padding:6px 14px;border-radius:8px;color:#9DA2B0;border:1px solid rgba(255,255,255,0.05);transition:all 0.15s}
.nav-back:hover{color:#E2E4EA;background:rgba(255,255,255,0.05)}

.wrapper{position:relative;z-index:1;max-width:1200px;margin:0 auto;padding:80px 16px 40px;min-height:100vh;display:flex;flex-direction:column}

.admin-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px}
.admin-header h1{font-size:1.6rem;font-weight:800;letter-spacing:-0.02em;color:#fff}
.admin-header h1 i{color:#A83D5C;margin-right:8px}
.admin-header h1 span{display:block;font-size:0.8rem;font-weight:500;color:#9DA2B0;letter-spacing:0}
.btn{display:inline-flex;align-items:center;gap:6px;padding:10px 18px;border-radius:10px;font-size:0.82rem;font-weight:600;border:none;transition:all 0.25s cubic-bezier(0.4,0,0.2,1)}
.btn:active{transform:scale(0.96)}
.btn-primary{background:#A83D5C;color:#fff;box-shadow:0 0 0 rgba(168,61,92,0)}
.btn-primary:hover{background:#8C2848;box-shadow:0 4px 20px rgba(168,61,92,0.4);transform:translateY(-1px)}
.btn-secondary{background:rgba(255,255,255,0.05);color:#E2E4EA;border:1px solid rgba(255,255,255,0.08)}
.btn-secondary:hover{background:rgba(255,255,255,0.09)}
.btn-rose{background:rgba(168,61,92,0.15);color:#A83D5C;border:1px solid rgba(168,61,92,0.2)}
.btn-rose:hover{background:#A83D5C;color:#fff;box-shadow:0 0 20px rgba(168,61,92,0.3)}
.btn-sm{padding:6px 12px;font-size:0.7rem;border-radius:6px}

.stats-bar{margin-bottom:24px}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px}
.stat-card{background:rgba(22,27,38,0.8);border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:16px 16px;text-align:center;backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px)}
.stat-card .num{font-size:2rem;font-weight:800;color:#A83D5C;line-height:1}
.stat-card .lab{font-size:0.6rem;font-weight:600;color:#9DA2B0;text-transform:uppercase;letter-spacing:0.04em;margin-top:4px}

.table-wrap{background:rgba(22,27,38,0.8);border:1px solid rgba(255,255,255,0.06);border-radius:16px;overflow:hidden;backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px)}
table{width:100%;border-collapse:collapse}
thead{background:rgba(0,0,0,0.2)}
th{font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#9DA2B0;text-align:left;padding:14px 16px;border-bottom:1px solid rgba(255,255,255,0.06)}
td{padding:12px 16px;font-size:0.82rem;border-bottom:1px solid rgba(255,255,255,0.03);color:#C8CAD1}
tr:last-child td{border-bottom:none}
tr:hover td{background:rgba(255,255,255,0.015)}
.brand-img {
        width: 32px;
        height: 32px;
    }

.badge{display:inline-block;padding:2px 10px;border-radius:20px;font-size:0.62rem;font-weight:600;text-transform:uppercase;letter-spacing:0.03em;background:rgba(168,61,92,0.15);color:#A83D5C}
.badge-police{background:rgba(52,152,219,0.15);color:#3498DB}
.badge-fire{background:rgba(230,126,34,0.15);color:#E67E22}
.badge-hospital{background:rgba(46,204,113,0.15);color:#2ECC71}
.badge-medical{background:rgba(155,89,182,0.15);color:#9B59B6}
.badge-disaster{background:rgba(231,76,60,0.15);color:#E74C3C}
.badge-generic{background:rgba(149,165,166,0.15);color:#95A5A6}

.actions-cell{display:flex;gap:4px;flex-wrap:wrap}
.actions-cell button{padding:5px 10px;border-radius:6px;border:none;background:rgba(255,255,255,0.04);color:#9DA2B0;font-size:0.82rem;transition:all 0.2s}
.actions-cell button:hover{background:rgba(168,61,92,0.15);color:#A83D5C}
.actions-cell .btn-del:hover{background:rgba(231,76,60,0.2);color:#E74C3C}

.mtk{display:none;position:fixed;inset:0;z-index:200;justify-content:center;align-items:center;padding:16px}
.mtk-overlay{position:absolute;inset:0;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px)}
.mtk.open{display:flex}
.mtk.open .mtk-box{animation:slideUp 0.3s cubic-bezier(0.4,0,0.2,1) forwards}
@keyframes slideUp{
from{opacity:0;transform:translateY(30px) scale(0.96)}
to{opacity:1;transform:translateY(0) scale(1)}
}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
.mtk-box{position:relative;z-index:1;background:rgba(18,22,40,0.96);border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:30px;width:100%;max-width:480px;max-height:90vh;overflow-y:auto;backdrop-filter:blur(40px);-webkit-backdrop-filter:blur(40px);box-shadow:0 30px 80px rgba(0,0,0,0.6)}
.mtk-box h2{font-size:1.3rem;font-weight:700;color:#fff;margin-bottom:6px}
.mtk-box p{font-size:0.78rem;color:#9DA2B0;margin-bottom:20px}
.mtk-box form,.mtk-box .modal-body{display:flex;flex-direction:column;gap:14px}
.mtk-box label{font-size:0.68rem;font-weight:600;color:#9DA2B0;text-transform:uppercase;letter-spacing:0.04em}
.mtk-box input,.mtk-box select,.mtk-box textarea{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:8px;padding:10px 14px;font-size:0.9rem;color:#E2E4EA;font-family:inherit;transition:all 0.2s;width:100%}
.mtk-box input:focus,.mtk-box select:focus,.mtk-box textarea:focus{outline:none;border-color:#A83D5C;box-shadow:0 0 0 2px rgba(168,61,92,0.2)}
.mtk-box input::placeholder{color:#626878}
.mtk-box textarea{resize:vertical;min-height:80px}
.mtk-box label{display:flex;flex-direction:column;gap:4px}
.mtk-box select{color:#E2E4EA}
.mtk-actions{display:flex;gap:8px;margin-top:8px}
.mtk-box .btn{flex:1;justify-content:center}

.del-box{max-width:400px;text-align:center;padding:40px 30px}
.del-box h2{color:#E74C3C}
.del-actions{display:flex;gap:10px;justify-content:center;margin-top:20px}

.err{display:none}
.conf{display:none;background:rgba(46,204,113,0.12);border:1px solid rgba(46,204,113,0.2);color:#2ECC71;padding:12px 18px;border-radius:12px;margin-bottom:16px;font-size:0.82rem;font-weight:500;align-items:center;gap:8px}
.conf.show{display:flex}

@media(max-width:768px){
table{display:block}
table thead{display:none}
table tbody,table tr,table td{display:block;width:100%}
table tr{background:rgba(22,27,38,0.8);border:1px solid rgba(255,0,255,0.01);margin-bottom:12px;border-radius:12px;overflow:hidden;padding:8px 0}
table tr:last-child{margin-bottom:0}
td{padding:6px 14px 6px 110px;position:relative;border-bottom:1px solid rgba(255,255,255,0.03)}
td::before{position:absolute;left:14px;top:6px;font-weight:600;font-size:0.6rem;text-transform:uppercase;color:#9DA2B0}
.actions-cell{display:flex;gap:6px;padding:10px 14px;background:rgba(0,0,0,0.1);border-bottom:none}
.actions-cell::before{content:'Actions';top:10px}
.actions-cell .btn,.actions-cell button{flex:1;justify-content:center}
td:last-child{border-bottom:none}
.d-mb{display:none}
td:nth-of-type(1)::before{content:'Name'}
td:nth-of-type(2)::before{content:'Type'}
td:nth-of-type(3)::before{content:'Phone'}
td:nth-of-type(4)::before{content:'Description'}
th:last-child{text-align:right}

.plus-mobile{position:fixed;bottom:20px;right:20px;z-index:99;width:56px;height:56px;border-radius:50%;background:#A83D5C;color:#fff;border:none;font-size:1.5rem;display:flex;box-shadow:0 6px 30px rgba(168,61,92,0.5);transition:all 0.2s}
.plus-mobile:active{transform:scale(0.92)}
}

@media(min-width:769px){.nf{display:none};
td:nth-of-type(1)::before{display:none}
td:nth-of-type(2)::before{display:none}
td:nth-last-of-type(2){
    min-width:100px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
    max-width:220px;
}
}
</style>
</head>
<body>

<div class="nav">
  <div class="nav-in">
    <a href="/" class="nav-brand">
      <img src="Logo.png" alt="Koronadal City" class="brand-img" />
      <div>
        <strong>City of Koronadal</strong>
        <small>Portal</small>
      </div>
    </a>
    <div class="nav-r">
      <span style="font-size:0.7rem;color:#A83D5C;font-weight:600;white-space:nowrap;">Emergency Settings</span>
      <a href="/" class="nav-back"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
  </div>
</div>

<div class="wrapper">
  <div class="admin-header">
    <h1>
      <i class="fas fa-truck-medical"></i> Emergency Contacts<br>
      <span>Koronadal City – Admin Panel</span>
    </h1>
    <div>
      <button class="btn btn-primary btn-open nf-plus" onclick="openAdd()"><i class="fas fa-plus"></i> Add new</button>
      <button class="nf btn btn-primary btn-open" onclick="openAdd()"><i class="fas fa-plus"></i> new</button>
    </div>
  </div>

  <div id="confMsg" class="conf">
    <i class="fas fa-check-circle"></i>
    <span id="confText"></span>
  </div>

  <div class="stats-bar">
    <div class="stats-grid">
      <div class="stat-card">
        <div class="num"><?= count($contacts) ?></div>
        <div class="lab">Total</div>
      </div>
      <div class="stat-card">
        <div class="num"><?= count(array_filter($contacts, fn($c) => $c['type'] === 'police')) ?></div>
        <div class="lab">Police</div>
      </div>
      <div class="stat-card">
        <div class="num"><?= count(array_filter($contacts, fn($c) => $c['type'] === 'fire')) ?></div>
        <div class="lab">Fire</div>
      </div>
      <div class="stat-card">
        <div class="num"><?= count(array_filter($contacts, fn($c) => $c['type'] === 'hospital' || $c['type'] === 'medical')) ?></div>
        <div class="lab">Medical</div>
      </div>
    </div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Type</th>
          <th>Phone</th>
          <th class="d-mb">Description</th>
          <th style="text-align:right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($contacts as $c): ?>
        <tr>
          <td><strong style="color:#E2E4EA"><?= htmlspecialchars($c['name'], ENT_QUOTES) ?></strong></td>
          <td>
            <span class="badge badge-<?= htmlspecialchars($c['type'], ENT_QUOTES) ?>">
              <?= htmlspecialchars(ucfirst($c['type']), ENT_QUOTES) ?>
            </span>
          </td>
          <td><?= htmlspecialchars($c['phone'], ENT_QUOTES) ?></td>
          <td class="d-mb" style="font-size:0.78rem;color:#9DA2B0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:240px;">
            <?= htmlspecialchars($c['description'] ?? '', ENT_QUOTES) ?>
          </td>
          <td>
            <div class="actions-cell" style="justify-content:flex-end">
              <button onclick="openEdit(<?= $c['id'] ?>)" title="Edit"><i class="fas fa-pencil-alt"></i></button>
              <button class="btn-del" onclick="openDel(<?= $c['id'] ?>)" title="Delete"><i class="fas fa-trash-alt"></i></button>
            </div>
          </td>
        </tr>
        <?php endforeach ?>
        <?php if (empty($contacts)): ?>
        <tr><td colspan="5" style="padding:40px;text-align:center;color:#9DA2B0;">No emergency contacts yet. Click "Add New" to get started.</td></tr>
        <?php endif ?>
      </tbody>
    </table>
  </div>

  <div style="flex:1;"></div>
  <div style="margin-top:24px;text-align:center;font-size:0.65rem;color:#626878;padding:12px 0;">
    &copy; <?= date('Y') ?> Koronadal City. All rights reserved.
  </div>
</div>

<div class="mtk" id="addModal">
  <div class="mtk-overlay" onclick="closeModal()"></div>
  <div class="mtk-box">
    <h2 id="modalTitle">Add Contact</h2>
    <p id="modalSubtitle">Fill in the details below</p>
    <form method="POST" id="modalForm">
      <input type="hidden" name="action" id="modalAction" value="add">
      <input type="hidden" name="id" id="modalId" value="0">
      <label>
        Name
        <input type="text" name="name" id="inputName" placeholder="e.g. Emergency Response Unit" required>
      </label>
      <label>
        Type
        <select name="type" id="inputType">
          <option value="general">General</option>
          <option value="police">Police</option>
          <option value="fire">Fire</option>
          <option value="hospital">Hospital</option>
          <option value="medical">Medical</option>
          <option value="disaster">Disaster</option>
        </select>
      </label>
      <label>
        Phone
        <input type="tel" name="phone" id="inputPhone" placeholder="e.g. 911 or (083) xxx-xxxx" required>
      </label>
      <label>
        Description
        <textarea name="desc" id="inputDesc" placeholder="Describe the service"></textarea>
      </label>
      <div class="mtk-actions">
        <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn btn-primary btn-save">Save</button>
      </div>
    </form>
  </div>
</div>

<div class="mtk" id="deleteModal">
  <div class="mtk-overlay" onclick="closeDel()"></div>
  <div class="mtk-box del-box">
    <h2><i class="fas fa-exclamation-triangle"></i> Delete</h2>
    <p>Are you sure you want to delete this contact? This cannot be undone.</p>
    <form method="POST" id="delForm">
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="id" id="delId" value="0">
      <div class="del-actions">
        <button type="button" class="btn btn-secondary" onclick="closeDel()">Cancel</button>
        <button type="submit" class="btn btn-primary" style="background:#d33;box-shadow:none;hover:background:#c22;">Delete</button>
      </div>
    </form>
  </div>
</div>

<script>
const contacts = <?= json_encode($contacts, JSON_UNESCAPED_UNICODE) ?>;

function openAdd() {
  document.getElementById('modalTitle').textContent = 'Add New Contact';
  document.getElementById('modalSubtitle').textContent = 'Enter the details for the new emergency contact.';
  document.getElementById('modalAction').value = 'add';
  document.getElementById('modalId').value = '';
  document.getElementById('inputName').value = '';
  document.getElementById('inputType').value = 'general';
  document.getElementById('inputPhone').value = '';
  document.getElementById('inputDesc').value = '';
  document.getElementById('addModal').classList.add('open');
}

function openEdit(id) {
  const c = contacts.find(x => x.id === id);
  if (!c) return;
  document.getElementById('modalTitle').textContent = 'Edit Contact';
  document.getElementById('modalSubtitle').textContent = 'Update the contact information.';
  document.getElementById('modalAction').value = 'edit';
  document.getElementById('modalId').value = c.id;
  document.getElementById('inputName').value = c.name;
  document.getElementById('inputType').value = c.type;
  document.getElementById('inputPhone').value = c.phone;
  document.getElementById('inputDesc').value = c.description || '';
  document.getElementById('addModal').classList.add('open');
}

function closeModal() {
  document.getElementById('addModal').classList.remove('open');
}

function openDel(id) {
  document.getElementById('delId').value = id;
  document.getElementById('deleteModal').classList.add('open');
}

function closeDel() {
  document.getElementById('deleteModal').classList.remove('open');
}

document.getElementById('addModal').querySelector('.mtk-overlay').addEventListener('click', closeModal);
document.getElementById('deleteModal').querySelector('.mtk-overlay').addEventListener('click', closeDel);

window.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeModal();
    closeDel();
  }
});

(function() {
  var urlParams = new URLSearchParams(window.location.search);
  var msg = urlParams.get('msg');
  if (msg) {
    var conf = document.getElementById('confMsg');
    var confText = document.getElementById('confText');
    var messages = {
      'added': 'Contact added successfully.',
      'updated': 'Contact updated successfully.',
      'deleted': 'Contact deleted successfully.'
    };
    if (messages[msg]) {
      confText.textCotent = messages[msg];
      confText.textContent = messages[msg];
      conf.classList.add('show');
      setTimeout(function() { conf.classList.remove('show'); }, 4000);
    }
    window.history.replaceState({}, '', window.location.pathname);
  }
})();
</script>
</body>
</html>
