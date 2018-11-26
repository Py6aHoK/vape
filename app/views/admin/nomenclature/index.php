<script>const TABLE = 'nomenclature';</script>
<top-panel>
    <ul>
        <a href="/admin"><li>Общая информация</li></a>
        <a href="/admin/nomenclature" class="active"><li>Номенклатура</li></a>
        <a href="/admin/discounts"><li>Скидки</li></a>
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
            <div class="dialog" id="dialog" table-name="nomenclatures">
                <section class="errors" id="errors" style="display:none;"></section>
                <section>
                    <label for="d_type">Раздел:</label>
                    <select id="d_type">
                        <?php foreach($nomenclatureTypes as $nomenclatureType):?>
                            <option value="<?php echo $nomenclatureType['id'];?>" <?php if($typeId == $nomenclatureType['id']){echo " selected";}?>>
                                <?php echo $nomenclatureType['naim'];?>
                            </option>
                        <?php endforeach;?>
                    </select>
                </section>
                <section>
                    <label for="d_name">Наименование:</label>
                    <input id="d_name" type="text" name="d_name" placeholder="Наименование" value="">
                </section>
                <section>
                    <label for="d_min_cost">Минимальная цена:</label>
                    <input id="d_min_cost" type="number" step="0.01" name="d_min_cost" placeholder="Минимальная цена" value="">
                </section>
                <section>
                    <label for="d_cost">Цена:</label>
                    <input id="d_cost" type="number" step="0.01" name="d_cost" placeholder="Цена" value="">
                </section>
                <section>
                    <label for="d_state">Статус:</label>
                    <select id="d_state">
                        <option value="1">Активен</option>
                        <option value="0">Удален</option>
                    </select>
                </section>
                <section>
                    <span id="save-btn">Сохранить</span>
                </section>
                <section>
                    <span id="cancel-btn">Отмена</span>
                    <input type="hidden" id="d_id" name="d_id">
                </section>
            </div>
        </div>
    </div>
    
    <filter-panel>
        <?php if(count($nomenclatureTypes) > 0):?>
            <label>Раздел для отображения:
                <select id="filter-types">
                    <?php foreach($nomenclatureTypes as $nomenclatureType):?>
                        <option value="<?php echo $nomenclatureType['id'];?>" <?php if($typeId == $nomenclatureType['id']){echo " selected";}?>>
                            <?php echo $nomenclatureType['naim'];?>
                        </option>
                    <?php endforeach;?>
                </select>
            </label>
        <?php endif;?>
        <label><input type="checkbox" id="filter-state">Отображать помеченные как удаленные</label>
    </filter-panel>
    <data-grid-wrapper>
        <data-grid>
            <table cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>Наименование</th>
                        <th width="80">Артикул</th>
                        <th width="100">Мин. цена</th>
                        <th width="100">Цена</th>
                        <th width="120"></th>
                    </tr>
                </thead>
                <tbody id="grid-rows" table="nomenclature">
                    <?php if(count($nomenclaturesList) > 0):?>
                        <?php foreach($nomenclaturesList as $nomenclatureItem):?>
                            <tr class="grid-row" row-id="<?php echo $nomenclatureItem['id'];?>">
                                <td><?php echo $nomenclatureItem['name'];?></td>
                                <td><?php echo $nomenclatureItem['id'];?></td>
                                <td><?php echo $nomenclatureItem['min_cost'];?></td>
                                <td><?php echo $nomenclatureItem['cost'];?></td>
                                <td class="state-btn"><?php echo ($nomenclatureItem['state'] == 0)? 'восстановить' : 'удалить';?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                </tbody>
            </table>
        </data-grid>
    </data-grid-wrapper>
</page-content-wrapper>