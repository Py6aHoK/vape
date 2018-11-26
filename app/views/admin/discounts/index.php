<script>const TABLE = 'discounts';</script>
<top-panel>
    <ul>
        <a href="/admin"><li>Общая информация</li></a>
        <a href="/admin/nomenclature"><li>Номенклатура</li></a>
        <a href="/admin/discounts" class="active"><li>Скидки</li></a>
        <a href="/admin/orders"><li>Чеки</li></a>
        <a href="/admin/users"><li>Пользователи</li></a>
    </ul>
    <span id="add-btn">Добавить</span>
    <a href="/logout" class="exit">Выход</a>
    <span class="user-name"><?php echo $userName;?></span>
</top-panel>
<page-content-wrapper>
    <div class="dialog-wrapper" style="display:none;">
        <div>
            <div class="dialog" id="dialog" table-name="discounts">
                <section class="errors" id="errors" style="display:none;"></section>
                <section>
                    <label for="ds_fio">ФИО держателя:</label>
                    <input id="ds_fio" type="text" name="ds_fio" placeholder="ФИО держателя" value="">
                </section>
                <section>
                    <label for="ds_number">Номер карты:</label>
                    <input id="ds_number" type="number" name="ds_number" placeholder="Номер карты" value="">
                </section>
                <section>
                    <label for="ds_value">Процент скидки:</label>
                    <input id="ds_value" type="number" name="ds_value" placeholder="Процент скидки" value="">
                </section>
                <section>
                    <label for="ds_state">Статус:</label>
                    <select id="ds_state">
                        <option value="1">Активен</option>
                        <option value="0">Удален</option>
                    </select>
                </section>
                <section>
                    <span id="save-btn">Сохранить</span>
                </section>
                <section>
                    <span id="cancel-btn">Отмена</span>
                    <input type="hidden" id="ds_id" name="ds_id">
                </section>
            </div>
        </div>
    </div>
    <filter-panel>
        <label><input type="checkbox" id="filter-state">Отображать помеченные как удаленные</label>
    </filter-panel>
    <data-grid-wrapper>
        <data-grid>
            <table cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>ФИО держателя</th>
                        <th width="100">Номер карты</th>
                        <th width="100">% скидки</th>
                        <th width="120"></th>
                    </tr>
                </thead>
                <tbody id="grid-rows" table="discounts">
                    <?php if(count($discountsList) > 0):?>
                        <?php foreach($discountsList as $discountItem):?>
                            <tr class="grid-row" row-id="<?php echo $discountItem['ds_id'];?>">
                                <td><?php echo $discountItem['ds_fio'];?></td>
                                <td><?php echo $discountItem['ds_number'];?></td>
                                <td><?php echo $discountItem['ds_value'];?></td>
                                <td class="state-btn"><?php echo ($discountItem['ds_state'] == 0)? 'восстановить' : 'удалить';?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                </tbody>
            </table>
        </data-grid>
    </data-grid-wrapper>
</page-content-wrapper>