<?php
declare(strict_types=1);
session_start();
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');
header('X-Frame-Options: DENY');
require_once __DIR__ . '/../../config/config.php';

// Configurar WHATSAPP_ADMIN_PASSWORD_HASH como variable de entorno del servidor
// o $WHATSAPP_ADMIN_PASSWORD_HASH en config/config.php (fuera del directorio público).
$passwordHash = (string) (getenv('WHATSAPP_ADMIN_PASSWORD_HASH') ?: ($WHATSAPP_ADMIN_PASSWORD_HASH ?? ''));
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php'); exit;
    }
    if (!$passwordHash) $error = 'Falta configurar la contraseña de administración.';
    elseif (!password_verify((string) ($_POST['password'] ?? ''), $passwordHash)) $error = 'Contraseña incorrecta.';
    else {
        session_regenerate_id(true);
        $_SESSION['loa_whatsapp_authenticated'] = true;
        $_SESSION['loa_whatsapp_csrf'] = bin2hex(random_bytes(32));
        header('Location: index.php'); exit;
    }
}
$authenticated = !empty($_SESSION['loa_whatsapp_authenticated']) && (bool) $passwordHash;
if ($authenticated && empty($_SESSION['loa_whatsapp_csrf'])) $_SESSION['loa_whatsapp_csrf'] = bin2hex(random_bytes(32));
?><!doctype html>
<html lang="es-AR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>LOA · WhatsApp a familias</title>
<style>
:root{font:16px/1.5 system-ui,-apple-system,Arial,sans-serif;color:#173d54;background:#f1f7f9}*{box-sizing:border-box}body{margin:0}header{background:#073c58;color:#fff;padding:19px max(20px,calc((100% - 1160px)/2));display:flex;justify-content:space-between;align-items:center}.logo{font-size:30px;letter-spacing:.08em;font-weight:900}.logo span{font-weight:400;letter-spacing:0;font-size:15px;color:#d2e8ef;margin-left:12px}main{max-width:1160px;margin:30px auto;padding:0 20px}h1{font-size:29px;margin:0}p{color:#68818e}.card,.stat{background:#fff;border:1px solid #dae9ee;border-radius:16px;padding:22px;box-shadow:0 6px 24px #183c4d0c;margin:18px 0}.card h2{font-size:19px;margin:0 0 12px}.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:18px 0}.stat{margin:0}.stat b{display:block;font-size:30px}.stat small,.muted{color:#6e8792;font-size:13px}textarea,input,select{font:inherit;border:1px solid #c5dbe4;border-radius:9px;padding:10px;color:#183d52}textarea{width:100%;min-height:150px;resize:vertical}button{font:inherit;background:#087d83;border:0;color:#fff;font-weight:700;padding:10px 14px;border-radius:9px;cursor:pointer}button.secondary{background:#e8f3f5;color:#175971}button.ghost{background:#fff;color:#3f7081;border:1px solid #c6dce3}button:disabled{opacity:.45;cursor:not-allowed}.row{display:flex;gap:10px;align-items:center;flex-wrap:wrap}.row input[type=search]{flex:1;min-width:200px}.table-wrap{overflow:auto}table{width:100%;border-collapse:collapse;min-width:770px;font-size:14px}td,th{text-align:left;padding:12px;border-bottom:1px solid #edf2f5}th{text-transform:uppercase;font-size:12px;letter-spacing:.05em;color:#668390}td:first-child{font-weight:600}.hint{font-size:12px;color:#7d929d}.phone{width:155px;border:1px solid transparent;background:transparent;padding:6px;cursor:text}.phone:hover{background:#edf7f8;border-color:#c4dce4}.phone:focus{background:white;border-color:#087d83;outline:1px solid #087d83}.pill{display:inline-block;padding:4px 9px;background:#eef2f4;border-radius:20px;color:#617b86;font-size:12px}.pill.sent{background:#e0f5e9;color:#116c46}.actions{display:flex;gap:6px;flex-wrap:wrap}.actions button{font-size:12px;padding:7px 9px}.pagination{justify-content:center;margin-top:16px}.alert{padding:12px;border-radius:9px;background:#fff2df;color:#8c531f;margin:12px 0}.login{max-width:410px;margin:70px auto}.login input{width:100%;margin:8px 0 12px}.login button{width:100%}.notice{font-size:13px;color:#65818d;margin-top:14px}@media(max-width:700px){.stats{grid-template-columns:repeat(3,1fr)}.stat{padding:13px}.stat b{font-size:24px}.card{padding:14px}header{padding:16px}.logo span{display:block;margin:0}main{margin:20px auto}}
</style></head><body>
<header><div class="logo">LOA <span>Mensajes a familias</span></div><?php if ($authenticated): ?><form method="post"><button class="secondary" type="submit" name="logout" value="1">Salir</button></form><?php endif; ?></header>
<?php if (!$authenticated): ?>
<main class="login"><section class="card"><h1>Acceso privado</h1><p>Ingresá la contraseña de administración para consultar los contactos de LOA.</p><?php if ($error): ?><div class="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?><form method="post"><label for="password">Contraseña</label><input type="password" id="password" name="password" autocomplete="current-password" required><button type="submit">Entrar</button></form></section></main>
<?php else: ?>
<main><h1>Mensajes a familias 💙</h1><p>Contactos sincronizados con la base de alumnos de LOA.</p>
<section class="card"><h2>✍️ Mensaje</h2><textarea id="message">¡Hola familias! 💙☀️

Les compartimos una novedad de LOA. Si tienen alguna duda o necesitan comunicarse con nosotros, pueden escribirnos por acá.

¡Nos vemos pronto! 💙🏄‍♂️</textarea><div class="row"><button id="saveMessage" class="secondary">Guardar mensaje</button><span class="muted">El texto se conserva en este navegador.</span></div></section>
<div class="stats"><div class="stat"><small>Contactos</small><b id="total">—</b></div><div class="stat"><small>Enviados</small><b id="sent">—</b></div><div class="stat"><small>Pendientes</small><b id="pending">—</b></div></div>
<section class="card"><h2>📋 Contactos</h2><div class="row"><input type="search" id="search" placeholder="Buscar familia o teléfono"><select id="filter"><option value="all">Todos</option><option value="pending">Pendientes</option><option value="sent">Enviados</option></select><button id="next" class="secondary">Abrir próximo pendiente</button></div><div id="error" class="alert" hidden></div><div class="table-wrap"><table><thead><tr><th>Alumnos</th><th>Contacto</th><th>WhatsApp (editable)</th><th>Estado</th><th>Acciones</th></tr></thead><tbody id="tbody"><tr><td colspan="5">Cargando…</td></tr></tbody></table></div><div class="row pagination"><button id="prev" class="ghost">Anterior</button><span id="pages" class="muted"></span><button id="forward" class="ghost">Siguiente</button></div><p class="notice">Los cambios se guardan en MySQL. Marcá «Enviado» solo después de enviar efectivamente el mensaje en WhatsApp; abrir el enlace no confirma el envío.</p></section>
</main>
<script>
const csrf=<?= json_encode($_SESSION['loa_whatsapp_csrf']) ?>;
const $=id=>document.getElementById(id);let contacts=[],page=0;
const escape=s=>String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const messageKey='loa-whatsapp-message-v1';$('message').value=localStorage.getItem(messageKey)||$('message').value;
function notice(msg){$('error').hidden=!msg;$('error').textContent=msg}
function normalized(raw){let s=String(raw).trim(),d=s.replace(/\D/g,'');if(d.startsWith('0054'))d=d.slice(2);if(d.startsWith('54')){if(d.length===12)d='549'+d.slice(2);return /^549\d{10}$/.test(d)?d:null}if(d.startsWith('0'))d=d.slice(1);if(/^\d{10}$/.test(d))return '549'+d;if(/^9\d{10}$/.test(d))return '54'+d;return /^\+[1-9]\d{7,14}$/.test(s)?d:null}
async function request(method,data){const r=await fetch('api.php',{method,credentials:'same-origin',headers:{'Content-Type':'application/json','X-CSRF-Token':csrf},...(data?{body:JSON.stringify(data)}:{})});const body=await r.json();if(!r.ok)throw Error(body.error||'Error de conexión');return body}
async function load(){try{contacts=(await request('GET')).contacts;render();notice('')}catch(e){notice('No se pudo cargar la base: '+e.message)}}
async function save(c,patch){const updated={...c,...patch};try{await request('POST',{source_phone:c.source_phone,phone:updated.phone,sent:!!updated.sent});Object.assign(c,updated);render();notice('')}catch(e){notice('No se guardaron los cambios: '+e.message);render()}}
function chat(c){let number=normalized(c.phone);if(!number){notice('El número no tiene un formato válido. Tocá el número para corregirlo.');return}let message=$('message').value.trim();localStorage.setItem(messageKey,message);window.open('https://wa.me/'+number+(message?'?text='+encodeURIComponent(message):''),'_blank','noopener,noreferrer')}
function list(){let q=$('search').value.toLocaleLowerCase().trim(),f=$('filter').value;return contacts.filter(c=>(f==='all'||(f==='sent')===c.sent)&&[c.phone,...c.students].join(' ').toLocaleLowerCase().includes(q))}
function render(){let rows=list(),pages=Math.max(1,Math.ceil(rows.length/15));page=Math.min(page,pages-1);$('total').textContent=contacts.length;$('sent').textContent=contacts.filter(c=>c.sent).length;$('pending').textContent=contacts.filter(c=>!c.sent).length;$('pages').textContent=`${page+1} / ${pages} · ${rows.length} contactos`;$('prev').disabled=page===0;$('forward').disabled=page===pages-1;
$('tbody').innerHTML=rows.slice(page*15,(page+1)*15).map(c=>`<tr><td>${escape(c.students.slice(0,2).join(' · '))}${c.students.length>2?`<div class="hint">+ ${c.students.length-2} alumnos</div>`:''}</td><td>${escape(c.roles.join(' / '))}</td><td><input class="phone" type="tel" inputmode="tel" data-source="${c.source_phone}" value="${escape(c.phone)}" title="Tocá para editar; Enter para guardar" aria-label="Número de WhatsApp"><div class="hint">Tocá para editar</div></td><td><span class="pill ${c.sent?'sent':''}">${c.sent?'Enviado':'Pendiente'}</span></td><td><div class="actions"><button data-action="chat" data-source="${c.source_phone}">WhatsApp</button><button class="${c.sent?'ghost':'secondary'}" data-action="toggle" data-source="${c.source_phone}">${c.sent?'Deshacer':'✓ Enviado'}</button></div></td></tr>`).join('')||'<tr><td colspan="5">No hay contactos.</td></tr>'}
document.addEventListener('click',e=>{let b=e.target.closest('[data-action]');if(!b)return;let c=contacts.find(x=>x.source_phone===b.dataset.source);if(!c)return;if(b.dataset.action==='chat')chat(c);if(b.dataset.action==='toggle')save(c,{sent:!c.sent})});
document.addEventListener('focusout',e=>{if(!e.target.matches('.phone'))return;let c=contacts.find(x=>x.source_phone===e.target.dataset.source);if(!c)return;let phone=normalized(e.target.value);if(!phone){notice('Formato inválido. Para Argentina usá 549 + código de área y celular.');e.target.value=c.phone;return}if(phone!==c.phone)save(c,{phone})});
document.addEventListener('keydown',e=>{if(e.target.matches('.phone')&&e.key==='Enter'){e.preventDefault();e.target.blur()}if(e.target.matches('.phone')&&e.key==='Escape'){const c=contacts.find(x=>x.source_phone===e.target.dataset.source);e.target.value=c.phone;e.target.blur()}});
$('search').oninput=()=>{page=0;render()};$('filter').onchange=()=>{page=0;render()};$('prev').onclick=()=>{page--;render()};$('forward').onclick=()=>{page++;render()};$('next').onclick=()=>{let c=list().find(x=>!x.sent);if(c)chat(c);else notice('No hay pendientes en esta lista.')};$('saveMessage').onclick=()=>{localStorage.setItem(messageKey,$('message').value);notice('Mensaje guardado en este navegador.')};load();
</script>
<?php endif; ?></body></html>
