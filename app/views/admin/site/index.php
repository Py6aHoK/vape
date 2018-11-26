<top-panel>
    <ul>
        <a href="/admin" class="active"><li>Общая информация</li></a>
        <a href="/admin/nomenclature"><li>Номенклатура</li></a>
        <a href="/admin/discounts"><li>Скидки</li></a>
        <a href="/admin/orders"><li>Чеки</li></a>
        <a href="/admin/users"><li>Пользователи</li></a>
    </ul>
    <a href="/logout" class="exit">Выход</a>
    <span class="user-name"><?php echo $userName;?></span>
</top-panel>
<widgets-panel>
    <?php foreach($widgets as $widget):?>
        <?php $widget->Render();?>
    <?php endforeach;?>
</widgets-panel>