<?php
// $publications, $courses
?>
<div class="card"><h2>Bem-vindo ao TECJ</h2><p class="small">Plataforma de exemplo com MVC + Repository.</p></div>
<div class="card"><h3>Cursos</h3><div class="grid"><?php foreach($courses as $c): ?><div class="card"><h4><?=htmlspecialchars($c['titulo'])?></h4><p class="small"><?=htmlspecialchars($c['descricao'])?></p><form method="post" action="/courses/enroll"><input type="hidden" name="course_id" value="<?=$c['id']?>"><button class="btn">Inscrever-se</button></form></div><?php endforeach; ?></div></div>
<div class="card"><h3>Feed</h3><?php foreach($publications as $p): ?><div class="card"><p><?=htmlspecialchars($p['conteudo'])?></p><p class="small">— <?=htmlspecialchars($p['cliente'] ?? 'Anônimo')?>, <?=htmlspecialchars($p['criado_em'])?></p></div><?php endforeach; ?></div>
