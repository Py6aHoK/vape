<script>const TABLE = 'orders';</script>
<top-panel>
    <ul>
        <a href="/admin"><li>Общая информация</li></a>
        <a href="/admin/nomenclature"><li>Номенклатура</li></a>
        <a href="/admin/discounts"><li>Скидки</li></a>
        <a href="/admin/orders" class="active"><li>Чеки</li></a>
        <a href="/admin/users"><li>Пользователи</li></a>
    </ul>
    <a href="/logout" class="exit">Выход</a>
    <span class="user-name"><?php echo $userName;?></span>
</top-panel>
<page-content-wrapper>
    <div class="dialog-wrapper" style="display:none;">
        <div>
            <div class="dialog" id="dialog" table-name="orders">
                <section class="errors" id="errors" style="display:none;"></section>
                <div class="order-info" id="order-info"></div>
                <section>
                    <span id="print-btn">Печать чека</span>
                </section>
                <section>
                    <span id="cancel-btn">Отмена</span>
                </section>
            </div>
        </div>
    </div>
    <data-grid-wrapper>
        <data-grid>
            <table cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th width="20">#</th>
                        <th>Пользователь</th>
                        <th width="80">Гостей</th>
                        <th width="80">Оплата</th>
                        <th width="200">Дата</th>
                        <th width="100">% скидки</th>
                        <th width="100">Сумма</th>
                    </tr>
                </thead>
                <tbody id="grid-rows" table="orders">
                    <?php if(count($ordersList) > 0):?>
                        <?php foreach($ordersList as $orderItem):?>
                            <tr class="grid-row" row-id="<?php echo $orderItem['o_id'];?>">
                                <td><?php echo $orderItem['o_id'];?></td>
                                <td><?php echo $orderItem['u_name'];?></td>
                                <td><?php echo $orderItem['guests'];?></td>
                                <td><?php echo ['Наличными','Картой'][$orderItem['o_card']];?></td>
                                <td><?php echo $orderItem['o_date'];?></td>
                                <td><?php echo $orderItem['o_discount'];?></td>
                                <td><?php echo $orderItem['summa'];?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                </tbody>
            </table>
        </data-grid>
    </data-grid-wrapper>
</page-content-wrapper>