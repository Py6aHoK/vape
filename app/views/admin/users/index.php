<script>const TABLE = 'users';</script>
<top-panel>
    <ul>
        <a href="/admin"><li>Общая информация</li></a>
        <a href="/admin/nomenclature"><li>Номенклатура</li></a>
        <a href="/admin/discounts"><li>Скидки</li></a>
        <a href="/admin/orders"><li>Чеки</li></a>
        <a href="/admin/users" class="active"><li>Пользователи</li></a>
    </ul>
    <span id="add-btn">Добавить</span>
    <a href="/logout" class="exit">Выход</a>
    <span class="user-name"><?php echo $userName;?></span>
</top-panel>

<page-content-wrapper>
    <div class="dialog-wrapper" style="display:none;">
        <div>
            <div class="dialog" id="dialog" table-name="users">
                <section class="errors" id="errors" style="display:none;"></section>
                <section>
                    <label for="u_name">Имя:</label>
                    <input id="u_name" type="text" name="u_name" placeholder="Имя пользователя" value="">
                </section>
                <section>
                    <label for="u_pass">Пароль:</label>
                    <input id="u_pass" type="password" name="u_pass" placeholder="Пароль" value="">
                </section>
                <section>
                    <label for="u_pass2">Подтверждение пароля:</label>
                    <input id="u_pass2" type="password" name="u_pass2" placeholder="Подтверждение пароля" value="">
                </section>
                <section>
                    <label for="u_rights">Права:</label>
                    <select id="u_rights">
                        <?php foreach(UserModel::RIGHTS as $key => $name):?>
                            <option value="<?php echo $key;?>"><?php echo $name;?></option>
                        <?php endforeach;?>
                    </select>
                </section>
                <section>
                    <label for="u_state">Статус:</label>
                    <select id="u_state">
                        <option value="1">Активен</option>
                        <option value="0">Удален</option>
                    </select>
                </section>
                <section>
                    <span id="save-btn">Сохранить</span>
                </section>
                <section>
                    <span id="cancel-btn">Отмена</span>
                    <input type="hidden" id="u_id" name="u_id">
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
                        <th>Имя пользователя</th>
                        <th width="300">Права</th>
                        <th width="120"></th>
                    </tr>
                </thead>
                <tbody id="grid-rows" table="users">
                    <?php if(count($usersList) > 0):?>
                        <?php foreach($usersList as $userItem):?>
                            <tr class="grid-row" row-id="<?php echo $userItem['u_id'];?>">
                                <td><?php echo $userItem['u_name'];?></td>
                                <td><?php echo UserModel::RIGHTS[$userItem['u_rights']];?></td>
                                <td class="state-btn"><?php echo ($userItem['u_state'] == 0)? 'восстановить' : 'удалить';?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                </tbody>
            </table>
        </data-grid>
    </data-grid-wrapper>
</page-content-wrapper>