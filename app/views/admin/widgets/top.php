<widget class="large">
    <widget-title>Топ блюд</widget-title>
    <widget-content>
        <?php if(count($topDishes) > 0):?>
            <div class="spacer"></div>
            <table>
                <thead>
                    <tr>
                        <th>Наименование</th>
                        <th style="width:60px;">Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($topDishes as $dish):?>
                        <tr><td><?php echo $dish['name'];?></td><td class="green"><?php echo $dish['summ'];?></td></tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        <?php else:?>
            <div style="line-height:240px;text-align:center;font-weight:bold;">Нет данных для сбора статистики</div>
        <?php endif;?>
    </widget-content>
</widget>