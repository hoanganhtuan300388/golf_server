<table>
    <thead>
        <th>
            <td>name</td>
            <td>address</td>
        </th>
    </thead>
    <tbody>
        <?php foreach($results as $result) { ?>
            <tr>
                <td><?= $result['name'] ?></td>
                <td><?= $result['address'] ?></td>
        <?php } ?>
    </tbody>
</table>
<!--<table>
    <thead>
        <th>
            <td>field_id</td>
            <td>version</td>
            <td>last_version_flg</td>
            <td>prefecture_id</td>
            <td>field_name</td>
            <td>field_name_kana</td>
            <td>field_name_en</td>
            <td>address</td>
            <td>field_long</td>
            <td>field_lat</td>
            <td>service_status</td>
            <td>phone</td>
            <td>update_by</td>
            <td>update_at</td>
            <td>insert_by</td>
            <td>insert_at</td>
            <td>delete_flg</td>
        </th>
    </thead>
    <tbody>
        <?php foreach($golfs as $golf) { ?>
            <tr>
                <td><?= $golf['field_id'] ?></td>
                <td><?= $golf['version'] ?></td>
                <td><?= $golf['last_version_flg'] ?></td>
                <td><?= $golf['prefecture_id'] ?></td>
                <td><?= $golf['field_name'] ?></td>
                <td><?= !empty($golf['field_name_kana']) ? $golf['field_name_kana'] : 'NULL'; ?></td>
                <td><?= !empty($golf['field_name_en']) ? $golf['field_name_en'] : 'NULL'; ?></td>
                <td><?= !empty($golf['address']) ? $golf['address'] : 'NULL'; ?></td>
                <td><?= $golf['field_long'] ?></td>
                <td><?= $golf['field_lat'] ?></td>
                <td><?= $golf['service_status'] ?></td>
                <td><?= $golf['phone'] ?></td>
                <td><?= !empty($golf['update_by']) ? $golf['update_by'] : 'NULL'; ?></td>
                <td><?= !empty($golf['update_at']) ? $golf['update_at'] : 'NULL' ?></td>
                <td><?= !empty($golf['insert_by']) ? $golf['insert_by'] : 'NULL' ?></td>
                <td><?= !empty($golf['insert_at']) ? $golf['insert_at'] : 'NULL' ?></td>
                <td><?= !empty($golf['delete_flg']) ? $golf['delete_flg'] : '0' ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>-->
