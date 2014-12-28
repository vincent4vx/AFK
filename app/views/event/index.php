<section id="contents">
    <h1>Évènements</h1>
    
    <article>
        <h2 class="title">Mes évènements</h2>
        <?php echo $this->loadView('event/event_list.php', array('events' => $org_events))?>
        <div class="button_container">
            <a href="<?php echo $this->url('createevent.php')?>" class="button">Créer un évènement</a>
        </div>
    </article>
    
    <?php if(!empty($par_events)):?>
    <article>
        <h2 class="title">Participations</h2>
        <?php echo $this->loadView('event/event_list.php', array('events' => $par_events))?>
    </article>
    <?php endif?>
</section>