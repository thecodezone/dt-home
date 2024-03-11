<?php
/**
 * @var array $app
 * @var string $url
 */
$this->layout('layouts/web-view');
?>

<div class="app-container">
    <iframe src="<?php echo htmlspecialchars($url); ?>" width="400" height="600" frameborder="0"></iframe>
    <a href="/home" class="icon-link">
        <sp-icon-view-grid class="sp-icon-view-grid"></sp-icon-view-grid>
    </a>
</div>

