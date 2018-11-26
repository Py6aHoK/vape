<widget class="large">
    <widget-title>Сотрудники</widget-title>
    <widget-content>
        <?php if(count($staffTable) > 0):?>
            <div class="spacer"></div>
            <table>
                <thead>
                    <tr>
                        <th>Сотрудник</th>
                        <th>Гостей</th>
                        <th>Средний чек</th>
                        <th style="width:60px;">Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($staffTable as $staff):?>
                        <tr>
                            <td><?php echo $staff['name'];?></td>
                            <td><?php echo $staff['guests'];?></td>
                            <td><?php echo $staff['avg'];?></td>
                            <td class="green"><?php echo $staff['summ'];?></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        <?php else:?>
            <div style="line-height:240px;text-align:center;font-weight:bold;">Нет данных для сбора статистики</div>
        <?php endif;?>
    </widget-content>
</widget>