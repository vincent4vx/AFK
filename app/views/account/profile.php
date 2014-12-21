<section id="left_menu">
    <h2>Profil</h2>
    <ul>
        <li>
            <?php if($this->getSession('id') == $user['USER_ID']):?>
            <a title="Changer d'image" href="<?php echo $this->url('image.php')?>">
            <?php endif?>
                <img class="avatar" src="<?php echo $user['AVATAR'] ? $this->secureUrl('image', 'get', $user['USER_ID'], $user['AVATAR']) : $this->url('resources/images/default.png')?>" alt="Avatar"/>
            <?php if($this->getSession('id') == $user['USER_ID']):?>
            </a>
            <?php endif?>
        </li>
        <li><strong>Nom :</strong> <?php echo $user['FIRST_NAME'], ' ', $user['LAST_NAME']?></li>
        <li><strong>Pseudo :</strong> <?php echo $user['PSEUDO']?></li>
        <li><?php echo $this->friendButton($user['USER_ID'])?></li>
    </ul>
</section>
<section id="contents">
    <h1><?php echo $user['PSEUDO']?></h1>
    <?php for($i = 0; $i < 100; ++$i):?>
    <article>
        <h2 class="title">titre</h2>
        <p>Contenue</p>
    </article>
    <?php endfor?>
</section>