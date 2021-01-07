<ul style="list-style: none; padding: 0;" class="SnMt-5 SnMb-5">
    <?php foreach ($parameter['censusFile'] as $row) : ?>
        <li style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--snColorBorder); padding: .5rem;">
            <div><i class="far fa-file SnMr-2"></i>file<?= $row['census_file_id'] ?></div>
            <?php if ($row['is_process'] == 0) : ?>
                <?php if($row['file_type'] == 'sql'): ?>
                    <div class="SnBtn icon primary" onclick="setQuery(<?= $row['census_file_id'] ?>)" title="Guardar"><i class="far fa-save"></i></div>
                <?php else: ?>
                    <div class="SnBtn icon primary" onclick="censusSetData(<?= $row['census_file_id'] ?>)" title="Guardar"><i class="far fa-save"></i></div>
                <?php endif; ?>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>