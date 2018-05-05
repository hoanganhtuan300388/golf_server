<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert alert-success alert-dismissible" onclick="this.classList.add('hidden')"><?= $message ?></div>
