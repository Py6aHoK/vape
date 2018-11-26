<form action="/login" method="POST" class="dialog">
    <?php if(!empty($errors)):?>
        <section class="errors"><span><?php echo $errors;?></span></section>
    <?php endif;?>
    <?php if(count($users) > 0):?>
        <section>
            <label for="id">Пользователь</label>
            <select id="id" name="id">
                <?php foreach ($users as $user):?>
                    <option value="<?php echo $user['u_id'];?>"><?php echo $user['u_name'];?></option>
                <?php endforeach;?>
            </select>
        </section>
        <section>
            <input type="password" name="pass" placeholder="Пароль">
        </section>
        <section>
            <input type="submit" value="Войти" class="btn-login">
            <input type="hidden" name="submit">
        </section>
    <?php else:?>
        Список пользователей пуст
    <?php endif;?>
</form>
