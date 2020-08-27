<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <meta name="description" content="<?= APP_DESCRIPTION ?>">
    <link rel="shortcut icon" href="<?= URL_PATH ?>/assets/images/icon/144.png">

    <?php require_once(__DIR__ . '/manifest.partial.php') ?>

    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/basic.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/fontawesome.css">

    <script src="<?= URL_PATH ?>/assets/script/helpers/sedna.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/theme.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/conmon.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="BasicLayout" id="BasicLayout">
        <div class="BasicLayout-header">
            <h1 class="Branding">
                <a href="<?php echo URL_PATH ?>" class="Branding-header">
                    <img src="<?php echo URL_PATH ?>/assets/images/icon/144.png" alt="" class="Branding-img">
                    <div> <?php echo APP_NAME ?> </div>
                </a>
                <div class="Branding-description"><?= APP_DESCRIPTION ?></div>
            </h1>
        </div>
        <div class="BasicLayout-main">
            <?php echo $content ?>
        </div>
        <div class="BasicLayout-footer">
            Copyright © <?= date('Y') ?> <?= APP_AUTHOR ?>
        </div>
    </div>
</body>

</html>