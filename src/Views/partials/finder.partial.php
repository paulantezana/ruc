<div>
    <?php foreach ($parameter['directory'] as $row): $fileType = pathinfo($row, PATHINFO_EXTENSION); ?>
        <?php if($fileType === 'txt' || $fileType === 'gitignore' || $fileType === 'zip'): ?>
            <?php if($fileType !== 'gitignore'): ?>
                <div><i class="far fa-file SnMr-2"></i><?= $row ?><span onclick="deleteFileWrapper('<?= $row ?>')" class="fas fa-times"></span></div>
            <?php else: ?>
                <div><i class="far fa-file SnMr-2"></i><?= $row ?></div>
            <?php endif; ?>
        <?php else: ?>
            <div><i class="fas fa-folder SnMr-2"></i><?= $row ?></div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>