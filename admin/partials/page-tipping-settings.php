<?php
$default_tab = null;
$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

?>
<div class="wrap">
  
  <nav class="nav-tab-wrapper">
    <a href="?page=lnpw_tipping-settings&tab=tipping-box" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Tipping Box</a>
    <a href="?page=lnpw_tipping-settings&tab=tipping-banner" class="nav-tab <?php if($tab==='tipping-banner'):?>nav-tab-active<?php endif; ?>">Tipping Banner</a>
  </nav>

  <div class="tab-content">
  <?php switch($tab) :
    case 'tipping-banner':
        require_once __DIR__ . '/page-tipping-banner.php';
      break;
    default:
      require_once __DIR__ . '/page-tipping-box.php';
      break;
  endswitch; ?>
  </div>
</div>
<?php
