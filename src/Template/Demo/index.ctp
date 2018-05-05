<table>
    <?php foreach($golfsDisplay as $golfDisplay) { ?>
        <tr>
            <td><?= $golfDisplay['id'] ?></td>
            <td><?= $golfDisplay['name'] ?></td>
            <td><?= $golfDisplay['address'] ?></td>
            <td><?= $golfDisplay['shotnavi_url'] ?></td>
            <td><?= $golfDisplay['ngaynghi'] ?></td>
        </tr>
    <?php } ?>
</table>