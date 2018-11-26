<top-panel>
    <btn-new-order>Новый заказ</btn-new-order>
    <btn-new-guest>Добавить гостя</btn-new-guest>
    <?php if($_SESSION['user']['rights'] == 2):?>
        <a href="/admin" class="btn-admin-panel">Панель администратора</a>
    <?php endif;?>
    <a href="/logout" class="exit">Выход</a>
    <span class="user-name"><?php echo $userName;?></span>
</top-panel>
<page-content-wrapper>
    <div class="left-container-wrape">
        <div class="order-info-wrape">
            <order-info></order-info>
        </div>
        <div class="keypad">
            <percents-dialog>
                <card-selector>
                    <input id="card-number" placeholder="Введите номер карты"><btn-search-card>Найти</btn-search-card>
                </card-selector>
                <div>
                    <percent>0</percent><percent>5</percent><percent>10</percent><percent>15</percent><percent>20</percent>
                    <percent>25</percent><percent>30</percent><percent>35</percent><percent>40</percent><percent>45</percent>
                    <percent>50</percent><percent>55</percent><percent>60</percent><percent>65</percent><percent>70</percent>
                    <percent>75</percent><percent>80</percent><percent>85</percent><percent>90</percent><percent>95</percent>
                </div>
            </percents-dialog>
            <div class="left-panel">
                <btn-percents>Скидка на заказ <order-discount>0</order-discount>%</btn-percents>
                <div class="buttons">
                    <btn-plus>+</btn-plus>
                    <btn-minus>-</btn-minus>
                    <btn-remove>x</btn-remove>
                    <btn-percent>%</btn-percent>
                </div>
            </div>
            <div class="right-panel">
                <order-summa>0 р.</order-summa>
                <div class="pay-types">
                    <btn-pay-cash>Наличными</btn-pay-cash>
                    <btn-pay-card>Картой</btn-pay-card>
                </div>
            </div>
        </div>
    </div>
    <menu-container-wrapper>
        <menu-container>
            <nomenclature-types></nomenclature-types>
            <nomenclature-container></nomenclature-container>
        </menu-container>
    </menu-container-wrapper>
</page-content-wrapper>
<script src="/js/kassa.js"></script>
<script>NewOrder();</script>
