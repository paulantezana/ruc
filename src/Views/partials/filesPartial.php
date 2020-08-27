<ul>
    <?php foreach ($parameter['censusFile'] as $row) : ?>
        <li>
            <div>FILE <?= $row['census_file_id'] ?></div>
            <?php if ($row['is_process'] == 0) : ?>
                <div class="SnBtn primary" onclick="censusSetData(<?= $row['census_file_id'] ?>)">Guardar</div>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>