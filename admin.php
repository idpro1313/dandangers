<?php
/**
 * Админ-панель редактирования контента
 * Использует общий CSS сайта (modern-styles.css)
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Админ-панель | Tiles Survive Wiki</title>
  
  <link rel="icon" type="image/jpeg" href="/dangers.jpg">
  <link rel="stylesheet" href="/modern-styles.css">
  
  <style>
    /* === Админ-панель: дополнительные стили === */
    
    /* Сброс стилей сайта для админки */
    body.admin-body {
      background: var(--bg-dark);
    }
    
    body.admin-body .site-nav,
    body.admin-body .site-footer,
    body.admin-body .promo-banner {
      display: none !important;
    }
    
    /* Login Screen */
    .login-screen {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: var(--space-4);
    }
    
    .login-box {
      background: var(--bg-elevated);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: var(--space-8);
      width: 100%;
      max-width: 400px;
    }
    
    .login-box h1 {
      text-align: center;
      margin-bottom: var(--space-6);
      color: var(--primary);
    }
    
    .form-group {
      margin-bottom: var(--space-4);
    }
    
    .form-group label {
      display: block;
      margin-bottom: var(--space-2);
      color: var(--text-secondary);
      font-size: var(--font-sm);
    }
    
    .form-group input {
      width: 100%;
      padding: var(--space-3) var(--space-4);
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      color: var(--text-primary);
      font-size: var(--font-base);
    }
    
    .form-group input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.2);
    }
    
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: var(--space-2);
      padding: var(--space-3) var(--space-4);
      border: none;
      border-radius: var(--radius-md);
      font-size: var(--font-sm);
      font-weight: 600;
      cursor: pointer;
      transition: all var(--transition);
    }
    
    .btn-primary {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
      width: 100%;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
    }
    
    .btn-secondary {
      background: var(--bg-elevated);
      color: var(--text-primary);
      border: 1px solid var(--border);
    }
    
    .btn-secondary:hover {
      background: var(--bg-card-hover);
      border-color: var(--primary);
    }
    
    .btn-success {
      background: #238636;
      color: white;
    }
    
    .btn-success:hover {
      background: #2ea043;
    }
    
    .btn-danger {
      background: #da3633;
      color: white;
    }
    
    .btn-danger:hover {
      background: #f85149;
    }
    
    .error-message {
      color: #da3633;
      text-align: center;
      margin-top: var(--space-3);
      font-size: var(--font-sm);
    }
    
    /* Admin Panel */
    .admin-panel {
      display: none;
      min-height: 100vh;
    }
    
    .admin-header {
      background: var(--bg-elevated);
      border-bottom: 1px solid var(--border);
      padding: var(--space-4) var(--space-6);
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    
    .admin-header h1 {
      font-size: var(--font-lg);
      color: var(--primary);
      display: flex;
      align-items: center;
      gap: var(--space-2);
    }
    
    .admin-header .logo-link {
      display: flex;
      align-items: center;
      gap: var(--space-3);
      text-decoration: none;
      color: inherit;
    }
    
    .admin-header .logo-link img {
      width: 32px;
      height: 32px;
      border-radius: var(--radius-md);
    }
    
    .user-actions {
      display: flex;
      gap: var(--space-3);
      align-items: center;
    }
    
    .admin-container {
      display: grid;
      grid-template-columns: 300px 1fr;
      min-height: calc(100vh - 65px);
    }
    
    /* Sidebar */
    .sidebar {
      background: var(--bg-elevated);
      border-right: 1px solid var(--border);
      padding: var(--space-4);
      overflow-y: auto;
    }
    
    .sidebar h2 {
      font-size: var(--font-xs);
      text-transform: uppercase;
      color: var(--text-secondary);
      margin-bottom: var(--space-4);
      letter-spacing: 0.5px;
      padding: 0 var(--space-3);
    }
    
    .file-list {
      list-style: none;
    }
    
    .file-item {
      padding: var(--space-3) var(--space-4);
      border-radius: var(--radius-md);
      cursor: pointer;
      margin-bottom: var(--space-1);
      transition: all var(--transition);
      display: flex;
      align-items: center;
      gap: var(--space-3);
    }
    
    .file-item:hover {
      background: var(--bg-card-hover);
    }
    
    .file-item.active {
      background: var(--primary);
      color: white;
    }
    
    .file-item .file-icon {
      font-size: var(--font-lg);
      flex-shrink: 0;
    }
    
    .file-item .file-info {
      flex: 1;
      min-width: 0;
    }
    
    .file-item .file-name {
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      font-size: var(--font-sm);
    }
    
    .file-item .file-date {
      font-size: var(--font-xs);
      color: var(--text-secondary);
    }
    
    .file-item.active .file-date {
      color: rgba(255, 255, 255, 0.7);
    }
    
    /* Editor Area */
    .editor-area {
      padding: var(--space-6);
      overflow-y: auto;
      background: var(--bg-dark);
    }
    
    .editor-toolbar {
      display: flex;
      gap: var(--space-3);
      margin-bottom: var(--space-4);
      flex-wrap: wrap;
      align-items: center;
    }
    
    .editor-container {
      background: var(--bg-elevated);
      border-radius: var(--radius-lg);
      overflow: hidden;
      box-shadow: var(--shadow-lg);
      border: 1px solid var(--border);
    }
    
    /* Панель форматирования */
    .format-toolbar {
      display: flex;
      align-items: center;
      gap: var(--space-1);
      padding: var(--space-3) var(--space-4);
      background: var(--bg-card);
      border-bottom: 1px solid var(--border);
      flex-wrap: wrap;
    }
    
    .format-toolbar button {
      width: 36px;
      height: 36px;
      border: 1px solid var(--border);
      background: var(--bg-elevated);
      color: var(--text-primary);
      border-radius: var(--radius-sm);
      cursor: pointer;
      font-size: var(--font-sm);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all var(--transition);
    }
    
    .format-toolbar button:hover {
      background: var(--bg-card-hover);
      border-color: var(--primary);
    }
    
    .toolbar-divider {
      width: 1px;
      height: 24px;
      background: var(--border);
      margin: 0 var(--space-2);
    }
    
    /* iframe редактор */
    .editor-frame {
      width: 100%;
      min-height: 600px;
      border: none;
      background: var(--bg-dark);
    }
    
    #htmlEditor {
      width: 100%;
      min-height: 600px;
      padding: var(--space-4);
      background: #1e1e1e;
      color: #d4d4d4;
      border: none;
      font-family: 'Fira Code', 'Consolas', monospace;
      font-size: var(--font-sm);
      resize: vertical;
      line-height: 1.5;
    }
    
    /* Status Bar */
    .status-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: var(--space-3) var(--space-6);
      background: var(--bg-elevated);
      border-top: 1px solid var(--border);
      font-size: var(--font-sm);
      color: var(--text-secondary);
    }
    
    .status-indicator {
      display: flex;
      align-items: center;
      gap: var(--space-2);
    }
    
    .status-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #238636;
    }
    
    .status-dot.unsaved {
      background: var(--primary);
      animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.5; }
    }
    
    /* Toast Notifications */
    .toast-container {
      position: fixed;
      bottom: var(--space-6);
      right: var(--space-6);
      z-index: 1000;
    }
    
    .toast {
      background: var(--bg-elevated);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: var(--space-4) var(--space-5);
      margin-top: var(--space-3);
      box-shadow: var(--shadow-lg);
      display: flex;
      align-items: center;
      gap: var(--space-3);
      animation: slideIn 0.3s ease;
    }
    
    .toast.success { border-left: 4px solid #238636; }
    .toast.error { border-left: 4px solid #da3633; }
    .toast.info { border-left: 4px solid var(--primary); }
    
    @keyframes slideIn {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }
    
    /* Modal */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      display: none;
    }
    
    .modal {
      background: var(--bg-elevated);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: var(--space-6);
      width: 100%;
      max-width: 600px;
      max-height: 80vh;
      overflow-y: auto;
    }
    
    .modal h2 {
      margin-bottom: var(--space-4);
      color: var(--primary);
    }
    
    .modal-actions {
      display: flex;
      gap: var(--space-3);
      justify-content: flex-end;
      margin-top: var(--space-4);
    }
    
    .backup-list {
      max-height: 400px;
      overflow-y: auto;
    }
    
    .backup-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: var(--space-3) var(--space-4);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      margin-bottom: var(--space-2);
      background: var(--bg-card);
    }
    
    .backup-item:hover {
      background: var(--bg-card-hover);
      border-color: var(--primary);
    }
    
    /* Empty State */
    .empty-state {
      text-align: center;
      padding: var(--space-12) var(--space-6);
      color: var(--text-secondary);
    }
    
    .empty-state .icon {
      font-size: 64px;
      margin-bottom: var(--space-4);
    }
    
    .empty-state h2 {
      color: var(--text-primary);
      margin-bottom: var(--space-2);
    }
    
    /* Loading */
    .loading {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: var(--space-8);
    }
    
    .spinner {
      width: 40px;
      height: 40px;
      border: 3px solid var(--border);
      border-top-color: var(--primary);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .admin-container {
        grid-template-columns: 1fr;
      }
      
      .sidebar {
        display: none;
        position: fixed;
        top: 65px;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 99;
      }
      
      .sidebar.open {
        display: block;
      }
      
      .admin-header {
        padding: var(--space-3) var(--space-4);
      }
      
      .user-actions {
        gap: var(--space-2);
      }
      
      .btn {
        padding: var(--space-2) var(--space-3);
        font-size: var(--font-xs);
      }
      
      .editor-area {
        padding: var(--space-4);
      }
    }
  </style>
</head>
<body class="admin-body">

<!-- Login Screen -->
<div class="login-screen" id="loginScreen">
  <div class="login-box">
    <h1>🔐 Админ-панель</h1>
    <form id="loginForm">
      <div class="form-group">
        <label for="password">Пароль</label>
        <input type="password" id="password" placeholder="Введите пароль" required autocomplete="current-password">
      </div>
      <button type="submit" class="btn btn-primary">Войти</button>
      <p class="error-message" id="loginError" style="display: none;"></p>
    </form>
  </div>
</div>

<!-- Admin Panel -->
<div class="admin-panel" id="adminPanel">
  <header class="admin-header">
    <a href="/" class="logo-link" title="На главную">
      <img src="/dangers.jpg" alt="Logo">
      <h1>📝 Редактор контента</h1>
    </a>
    <div class="user-actions">
      <button class="btn btn-secondary" onclick="showBackups()">📦 Бэкапы</button>
      <button class="btn btn-secondary" onclick="previewPage()">👁 Просмотр</button>
      <button class="btn btn-danger" onclick="logout()">Выйти</button>
    </div>
  </header>
  
  <div class="admin-container">
    <aside class="sidebar" id="sidebar">
      <h2>📁 Файлы</h2>
      <ul class="file-list" id="fileList">
        <li class="loading"><div class="spinner"></div></li>
      </ul>
    </aside>
    
    <main class="editor-area">
      <div id="editorPlaceholder" class="empty-state">
        <div class="icon">📄</div>
        <h2>Выберите файл</h2>
        <p>Выберите страницу из списка слева для редактирования</p>
      </div>
      
      <div id="editorWrapper" style="display: none;">
        <div class="editor-toolbar">
          <button class="btn btn-success" onclick="saveContent()">💾 Сохранить</button>
          <button class="btn btn-secondary" onclick="reloadContent()">🔄 Обновить</button>
          <button class="btn btn-secondary" onclick="toggleHtmlMode()">⚡ HTML</button>
          <span id="currentFileName" style="margin-left: auto; color: var(--text-secondary);"></span>
        </div>
        
        <div class="editor-container">
          <div class="format-toolbar" id="formatToolbar">
            <button type="button" onclick="formatDoc('bold')" title="Жирный (Ctrl+B)"><b>B</b></button>
            <button type="button" onclick="formatDoc('italic')" title="Курсив (Ctrl+I)"><i>I</i></button>
            <button type="button" onclick="formatDoc('underline')" title="Подчёркнутый"><u>U</u></button>
            <span class="toolbar-divider"></span>
            <button type="button" onclick="formatBlock('h1')" title="Заголовок 1">H1</button>
            <button type="button" onclick="formatBlock('h2')" title="Заголовок 2">H2</button>
            <button type="button" onclick="formatBlock('h3')" title="Заголовок 3">H3</button>
            <button type="button" onclick="formatBlock('p')" title="Параграф">P</button>
            <span class="toolbar-divider"></span>
            <button type="button" onclick="formatDoc('insertUnorderedList')" title="Список">•</button>
            <button type="button" onclick="formatDoc('insertOrderedList')" title="Нумерация">1.</button>
            <button type="button" onclick="formatBlock('blockquote')" title="Цитата">❝</button>
            <span class="toolbar-divider"></span>
            <button type="button" onclick="insertLink()" title="Ссылка">🔗</button>
            <button type="button" onclick="formatDoc('removeFormat')" title="Очистить">✖</button>
          </div>
          
          <iframe id="editorFrame" class="editor-frame"></iframe>
        </div>
        
        <textarea id="htmlEditor" style="display: none;"></textarea>
      </div>
    </main>
  </div>
  
  <div class="status-bar">
    <div class="status-indicator">
      <div class="status-dot" id="statusDot"></div>
      <span id="statusText">Готов к работе</span>
    </div>
    <span id="lastSaved"></span>
  </div>
</div>

<!-- Backups Modal -->
<div class="modal-overlay" id="backupsModal">
  <div class="modal">
    <h2>📦 Резервные копии</h2>
    <div class="backup-list" id="backupList">
      <div class="loading"><div class="spinner"></div></div>
    </div>
    <div class="modal-actions">
      <button class="btn btn-secondary" onclick="closeBackupsModal()">Закрыть</button>
    </div>
  </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<script>
const API_URL = '/admin-api.php';

let editorFrame = null;
let editorDoc = null;
let currentFile = null;
let originalContent = '';
let isHtmlMode = false;
let hasUnsavedChanges = false;

// Инициализация iframe редактора
function initEditor() {
  editorFrame = document.getElementById('editorFrame');
  const frameDoc = editorFrame.contentDocument || editorFrame.contentWindow.document;
  
  frameDoc.open();
  frameDoc.write(`<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="/modern-styles.css">
  <style>
    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; min-height: 100%; }
    body {
      padding: 24px;
      background: var(--bg-dark, #0d1117);
      color: var(--text-primary, #f0f6fc);
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      line-height: 1.6;
      outline: none;
    }
    body:empty::before {
      content: 'Начните редактирование...';
      color: var(--text-secondary);
    }
    .site-nav, .site-footer, .promo-banner { display: none !important; }
    h1, h2, h3 { margin-top: 1em; margin-bottom: 0.5em; }
    p { margin-bottom: 1em; }
    a { color: var(--primary); }
    table { border-collapse: collapse; width: 100%; margin: 1em 0; }
    th, td { border: 1px solid var(--border); padding: 8px 12px; text-align: left; }
    th { background: var(--bg-elevated); }
    code { background: var(--bg-elevated); padding: 2px 6px; border-radius: 4px; }
    blockquote { border-left: 4px solid var(--primary); margin: 1em 0; padding-left: 1em; color: var(--text-secondary); }
    ul, ol { margin: 1em 0; padding-left: 2em; }
    li { margin-bottom: 0.5em; }
  </style>
</head>
<body contenteditable="true"></body>
</html>`);
  frameDoc.close();
  
  editorDoc = frameDoc;
  
  editorDoc.body.addEventListener('input', () => {
    hasUnsavedChanges = true;
    updateStatus('Есть несохранённые изменения', true);
  });
  
  editorDoc.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
      e.preventDefault();
      saveContent();
    }
  });
}

function formatDoc(command, value = null) {
  editorFrame.contentWindow.focus();
  editorDoc.execCommand(command, false, value);
}

function formatBlock(tag) {
  editorFrame.contentWindow.focus();
  editorDoc.execCommand('formatBlock', false, tag);
}

function insertLink() {
  const url = prompt('Введите URL:', 'https://');
  if (url) formatDoc('createLink', url);
}

function getEditorContent() {
  return editorDoc.body.innerHTML;
}

function setEditorContent(html) {
  editorDoc.body.innerHTML = html;
}

async function apiCall(action, data = {}, method = 'GET') {
  const url = new URL(API_URL, window.location.href);
  
  if (method === 'GET') {
    url.searchParams.append('action', action);
    Object.keys(data).forEach(key => url.searchParams.append(key, data[key]));
    const response = await fetch(url);
    return response.json();
  } else {
    const formData = new FormData();
    formData.append('action', action);
    Object.keys(data).forEach(key => formData.append(key, data[key]));
    const response = await fetch(API_URL, { method: 'POST', body: formData });
    return response.json();
  }
}

async function checkAuth() {
  const result = await apiCall('check_auth');
  if (result.authenticated) showAdminPanel();
}

async function login(e) {
  e.preventDefault();
  const password = document.getElementById('password').value;
  const result = await apiCall('login', { password }, 'POST');
  
  if (result.success) {
    showAdminPanel();
    showToast('Добро пожаловать!', 'success');
  } else {
    document.getElementById('loginError').textContent = result.message;
    document.getElementById('loginError').style.display = 'block';
  }
}

async function logout() {
  if (hasUnsavedChanges && !confirm('Есть несохранённые изменения. Выйти?')) return;
  await apiCall('logout', {}, 'POST');
  location.reload();
}

function showAdminPanel() {
  document.getElementById('loginScreen').style.display = 'none';
  document.getElementById('adminPanel').style.display = 'block';
  initEditor();
  loadFiles();
}

async function loadFiles() {
  const result = await apiCall('get_files');
  
  if (result.success) {
    const fileList = document.getElementById('fileList');
    fileList.innerHTML = '';
    
    result.files.forEach(file => {
      const li = document.createElement('li');
      li.className = 'file-item';
      li.onclick = () => loadFile(file.name);
      
      const icon = file.name.endsWith('.md') ? '📝' : '📄';
      li.innerHTML = `
        <span class="file-icon">${icon}</span>
        <div class="file-info">
          <div class="file-name">${file.display || file.name}</div>
          <div class="file-date">${file.modified}</div>
        </div>
      `;
      fileList.appendChild(li);
    });
  }
}

async function loadFile(filename) {
  if (hasUnsavedChanges && !confirm('Есть несохранённые изменения. Продолжить?')) return;
  
  updateStatus('Загрузка...', false);
  const result = await apiCall('get_content', { file: filename });
  
  if (result.success) {
    currentFile = filename;
    originalContent = result.content;
    
    document.getElementById('editorPlaceholder').style.display = 'none';
    document.getElementById('editorWrapper').style.display = 'block';
    document.getElementById('currentFileName').textContent = filename;
    
    setEditorContent(result.content);
    document.getElementById('htmlEditor').value = result.content;
    
    document.querySelectorAll('.file-item').forEach(item => {
      item.classList.remove('active');
      if (item.querySelector('.file-name').textContent === (result.display || filename)) {
        item.classList.add('active');
      }
    });
    
    hasUnsavedChanges = false;
    updateStatus('Файл загружен', false);
    showToast(`Загружен: ${result.display || filename}`, 'info');
  } else {
    showToast(result.error || 'Ошибка загрузки', 'error');
  }
}

async function saveContent() {
  if (!currentFile) return;
  
  updateStatus('Сохранение...', false);
  const content = isHtmlMode ? document.getElementById('htmlEditor').value : getEditorContent();
  
  const result = await apiCall('save_content', { file: currentFile, content: content }, 'POST');
  
  if (result.success) {
    hasUnsavedChanges = false;
    originalContent = content;
    updateStatus('Сохранено', false);
    document.getElementById('lastSaved').textContent = `Сохранено: ${new Date().toLocaleTimeString()}`;
    showToast('Файл сохранён!', 'success');
  } else {
    showToast(result.error || 'Ошибка сохранения', 'error');
  }
}

function reloadContent() {
  if (currentFile) loadFile(currentFile);
}

function toggleHtmlMode() {
  isHtmlMode = !isHtmlMode;
  const editorContainer = document.querySelector('.editor-container');
  const htmlEditor = document.getElementById('htmlEditor');
  
  if (isHtmlMode) {
    htmlEditor.value = getEditorContent();
    editorContainer.style.display = 'none';
    htmlEditor.style.display = 'block';
    showToast('HTML режим', 'info');
  } else {
    setEditorContent(htmlEditor.value);
    editorContainer.style.display = 'block';
    htmlEditor.style.display = 'none';
    showToast('Визуальный режим', 'info');
  }
}

function previewPage() {
  if (!currentFile) return;
  
  // Определяем URL для предпросмотра
  let previewUrl = '/';
  if (currentFile.startsWith('content/guides/')) {
    const slug = currentFile.replace('content/guides/', '').replace('.md', '');
    previewUrl = '/guide-' + slug + '.html';
  } else if (currentFile.startsWith('content/lootbar/')) {
    const slug = currentFile.replace('content/lootbar/', '').replace('.md', '');
    previewUrl = '/lootbar-' + slug + '.html';
  } else if (currentFile.startsWith('content/schedule/')) {
    previewUrl = '/daily.html';
  }
  
  window.open(previewUrl, '_blank');
}

async function showBackups() {
  document.getElementById('backupsModal').style.display = 'flex';
  const result = await apiCall('get_backups');
  const list = document.getElementById('backupList');
  
  if (result.success && result.backups.length > 0) {
    list.innerHTML = result.backups.map(b => `
      <div class="backup-item">
        <div>
          <strong>${b.name}</strong><br>
          <small style="color: var(--text-secondary);">${b.date} · ${(b.size / 1024).toFixed(1)} KB</small>
        </div>
        <button class="btn btn-secondary" onclick="restoreBackup('${b.name}')">Восстановить</button>
      </div>
    `).join('');
  } else {
    list.innerHTML = '<p style="text-align: center; color: var(--text-secondary); padding: 2rem;">Нет резервных копий</p>';
  }
}

async function restoreBackup(filename) {
  if (!confirm(`Восстановить из ${filename}?`)) return;
  
  const result = await apiCall('restore_backup', { backup: filename }, 'POST');
  
  if (result.success) {
    showToast(result.message, 'success');
    closeBackupsModal();
    if (currentFile) loadFile(currentFile);
  } else {
    showToast(result.error || 'Ошибка', 'error');
  }
}

function closeBackupsModal() {
  document.getElementById('backupsModal').style.display = 'none';
}

function updateStatus(text, unsaved) {
  document.getElementById('statusText').textContent = text;
  document.getElementById('statusDot').className = 'status-dot' + (unsaved ? ' unsaved' : '');
}

function showToast(message, type = 'info') {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `<span>${message}</span>`;
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 4000);
}

document.addEventListener('keydown', (e) => {
  if ((e.ctrlKey || e.metaKey) && e.key === 's') {
    e.preventDefault();
    saveContent();
  }
});

window.addEventListener('beforeunload', (e) => {
  if (hasUnsavedChanges) {
    e.preventDefault();
    e.returnValue = '';
  }
});

document.getElementById('loginForm').addEventListener('submit', login);
checkAuth();
</script>

</body>
</html>
