<table class="ui <?=themeClass?> table tablesearch selectable tablejobexec tablevisibility calendarreload compact" id="resultTable">
    <thead>
        <tr>
            <th class="collapsing"></th>
            <th>Date</th>
            <th>Durée</th>
            <th>Statut</th>
            <th>Groupe</th>
            <th>Tâche</th>
            <th class="access_column">Rundeck</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($rdkData as $data):?>
        <tr <?=$hide[$data['hidden']]["style"]?> class='<?=$data['job_id']?> <?=$hide[$data['hidden']]["class"]?>'>
            <td>
                <i class='eye outline <?=$hide[$data['hidden']]["icon"]?> icon tr_visibility' title='Afficher / Cacher le JOB' onclick="tr_visibility('<?=$data['job_id']?>');"></i>
            </td>
            <td><?=$data['human_date']?></td>
            <td title='Moyenne : <?=$data['avg']?>'><?=$data['duration']?></td>
            <td><?=$data['exec_status']?></td>
            <td><?=$data['job_group']?></td>
            <td><?=$data['job_name']?></td>
            <td class='access_column'><a href="<?=$rdk_url?>/project/<?=$rdk_project?>/job/show/<?=$data['job_id']?>" target="_blank">Job</a><?=$data['html_log']?></td>
        </tr>
    <?php endforeach?>
    </tbody>
</table>