<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <meta name="description" content="<?= APP_DESCRIPTION ?>">
    <link rel="shortcut icon" href="<?= URL_PATH ?>/assets/images/icon/144.png">

    <?php require_once(__DIR__ . '/manifest.partial.php') ?>

    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/prism.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/site.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/nprogress.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/fontawesome.css">

    <script>var URL_PATH = "<?= URL_PATH ?>"; </script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/prism.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/sedna.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/theme.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/nprogress.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/conmon.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">



</head>

<body>
    <!-- Load Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                xfbml            : true,
                version          : 'v9.0'
            });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/es_LA/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <!-- Your Chat Plugin code -->
    <div class="fb-customerchat"
        attribution=setup_tool
        page_id="764145183607069"
        theme_color="#0A7CFF"></div>
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
                                <li itemprop="url"><a itemprop="name" title="Inicio" href="<?= URL_PATH ?>/"><i class="fas fa-home SnMr-2"></i>Inicio</a></li>
                                <li itemprop="url"><a itemprop="name" title="Precios" href="<?= URL_PATH ?>/page/price"><i class="fas fa-frog SnMr-2"></i>Precios</a></li>
                                <?php if (isset($_SESSION[SESS_USER])) : ?>
                                    <li itemprop="url"><a itemprop="name" title="Token" href="<?= URL_PATH ?>/page/token"><i class="fas fa-key SnMr-2"></i>Tokens</a></li>
                                <?php endif; ?>
                                <?php if (isset($_SESSION[SESS_USER])) : ?>
                                    <li itemprop="url" class="SiteMenu-profile">
                                        <div class="HeaderMenu-profile Header-action">
                                            <div class="SnAvatar">
                                                <?php if ($_SESSION[SESS_USER]['avatar'] !== '') : ?>
                                                    <img class="SnAvatar-img" src="<?= URL_PATH ?><?= $_SESSION[SESS_USER]['avatar'] ?>" alt="avatar">
                                                <?php else : ?>
                                                    <div class="SnAvatar-text"><?= substr($_SESSION[SESS_USER]['user_name'], 0, 2); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <ul>
                                            <li class="User-item SnMt-2 SnMb-2">
                                                <a href="<?= URL_PATH ?>/admin/user/profile" class="SnAvatar">
                                                    <?php if ($_SESSION[SESS_USER]['avatar'] !== '') : ?>
                                                        <img class="SnAvatar-img" src="<?= URL_PATH ?><?= $_SESSION[SESS_USER]['avatar'] ?>" alt="avatar">
                                                    <?php else : ?>
                                                        <div class="SnAvatar-text"><?= substr($_SESSION[SESS_USER]['user_name'], 0, 2); ?></div>
                                                    <?php endif; ?>
                                                </a>
                                                <div>
                                                    <div class="User-title"><strong id="userTitleInfo"><?= $_SESSION[SESS_USER]['email'] ?></strong></div>
                                                    <div class="User-description" id="userDescriptionInfo"><?= $_SESSION[SESS_USER]['user_name'] ?></div>
                                                </div>
                                            </li>
                                            <li class="divider"></li>
                                            <li class="SnMt-2"><a href="<?= URL_PATH ?>/user/update"><i class="fas fa-user SnMr-2"></i>Perfil</a></li>
                                            <?php if ($_SESSION[SESS_USER]['user_role_id'] == 2) : ?>
                                                <li itemprop="url"><a itemprop="name" title="Token" href="<?= URL_PATH ?>/admin"><i class="fas fa-user-cog SnMr-2"></i>Administrador</a></li>
                                            <?php endif; ?>
                                            <li class="SnMb-2"><a href="<?= URL_PATH ?>/user/logout"><i class="fas fa-sign-out-alt SnMr-2"></i>Cerrar sesión</a></li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                            </ul>
                            <div class="SiteMenu-footer">
                                <?php if (!isset($_SESSION[SESS_KEY])) : ?>
                                    <a itemprop="name" title="Registrarse" href="<?= URL_PATH ?>/user/register" class="SnBtn primary">Registrarse</a>
                                    <a itemprop="name" title="Ingresar" href="<?= URL_PATH ?>/user/login" class="SnBtn">Ingresar</a>
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