<!doctype html>
<html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>TECJ</title>
<style>
body{font-family:Arial,Helvetica,sans-serif;background:#0f1720;color:#e6eef3;margin:0}
.header{background:#0b5cff;padding:14px;color:white;display:flex;justify-content:space-between;align-items:center}
.container{max-width:1000px;margin:18px auto;padding:0 16px}
.card{background:#122230;padding:18px;border-radius:10px;margin-bottom:16px;box-shadow:0 6px 18px rgba(0,0,0,0.4)}
.btn{background:#0b72d6;color:white;padding:10px 14px;border-radius:8px;text-decoration:none;border:none;cursor:pointer}
.input{width:100%;padding:10px;border-radius:8px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:inherit}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:12px}
.small{color:#9fb0bf;font-size:0.9rem}
.nav a{color:white;text-decoration:none;margin-left:12px}
</style>
</head><body>
<header class="header"><div><strong>TECJ</strong></div><nav class="nav"><a href="/">Início</a><a href="/courses">Cursos</a><a href="/vagas">Vagas</a><a href="/perfil">Perfil</a></nav></header>
<main class="container"><?php if(isset($viewPath)) include $viewPath; ?></main>
</body></html>
