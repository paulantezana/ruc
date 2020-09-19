<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <meta name="description" content="<?= APP_DESCRIPTION ?>">
    <link rel="shortcut icon" href="<?= URL_PATH ?>/assets/images/icon/144.png">

    <?php require_once(__DIR__ . '/manifest.partial.php') ?>

    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/site.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/nprogress.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/fontawesome.css">

    <script src="<?= URL_PATH ?>/assets/script/helpers/sedna.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/theme.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/nprogress.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/conmon.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="SiteLayout" id="SiteLayout">
        <div class="SiteLayout-header">
            <header class="SiteHeader">
                <div class="SiteHeader-left">
                    <a href="<?= URL_PATH ?>/" class="SiteBranding">
                        <img src="<?= URL_PATH ?>/assets/images/icon/144.png" alt="logo" class="SiteBranding-img">
                        <span class="SiteBranding-name"><?= APP_NAME ?></span>
                    </a>
                </div>
                <div class="SiteHeader-right">
                    <div id="SiteMenu-toggle"><i class="fas fa-bars"></i></div>
                    <nav class="SiteMenu-wrapper" id="SiteMenu-wrapper" itemscope itemtype="http://schema.org/SiteNavigationElement" role="navigation">
                        <div class="SiteMenu-content">
                            <div class="SiteMenu-header">
                                <div class="SiteBranding">
                                    <a href="<?= URL_PATH ?>/">
                                        <img src="<?php echo URL_PATH ?>/assets/images/icon/144.png" alt="logo">
                                        <span> <?php echo APP_NAME ?> </span>
                                    </a>
                                </div>
                            </div>
                            <ul class="SiteMenu" id="SiteMenu">
                                <li itemprop="url"><a itemprop="name" title="Incio" href="<?= URL_PATH ?>/">Inicio</a></li>
                                <li itemprop="url"><a itemprop="name" title="Precios" href="<?= URL_PATH ?>/page/price">Precios</a></li>
                                <li itemprop="url"><a itemprop="name" title="Soporte" href="<?= URL_PATH ?>/page/support">Soporte</a></li>
                            </ul>
                            <div class="SiteMenu-footer">
                                <?php if (!isset($_SESSION[SESS_KEY])) : ?>
                                    <a itemprop="name" title="Registrarse" href="<?= URL_PATH ?>/page/register" class="SnBtn primary">Registrarse</a>
                                    <a itemprop="name" title="Ingresar" href="<?= URL_PATH ?>/page/login" class="SnBtn">Ingresar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </nav>
                </div>
            </header>
        </div>
        <div class="SiteLayout-main">
            <?php echo $content ?>
        </div>
        <div class="SiteLayout-footer">
            Copyright © <?= date('Y') ?> <?= APP_AUTHOR ?>
        </div>
    </div>
    <script src="<?= URL_PATH ?>/assets/script/siteLayout.js"></script>
</body>

</html>