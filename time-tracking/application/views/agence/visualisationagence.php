
<style>
    table th {
        text-align: center;
        font-weight: bold;

    }
    .calendar_off {
        background-color: #FFFFFF !important;
    }
</style>
<div class="row container-fluid">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Tableau des agences</h2>
        </div>
    </div>

    <!--<div class="row">
        <div class="col-md-12">
            <form class="row gx-3 gy-2 align-items-center" method="GET">
                <div class="col-sm-3">
                    <label class="visually-hidden" for="calendrier-agence-du">Du</label>
                    <div class="input-group">
                        <div class="input-group-text">Du</div>
                        <input type="date" class="form-control" name="filtreCalendrierAgenceDu" id="calendrier-agence-du" placeholder="Du" value="<?= $filtreCalendrierAgenceDu ?>">
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="visually-hidden" for="calendrier-agence-au">Au</label>
                    <div class="input-group">
                        <div class="input-group-text">Au</div>
                        <input type="date" class="form-control" name="filtreCalendrierAgenceAu" id="calendrier-agence-au" placeholder="Au" value="<?= $filtreCalendrierAgenceAu ?>">
                    </div>
                </div>
                
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
                </form>
        </div> 
    </div>-->

    <div class="row mt-3 ">
        <div class="col-md-12">
            <table id="calendrier-agence" class="table-striped table-hover table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Code</th> 
                        <th>Agence</th>
                        <?php foreach($dates as $date): ?>
                            <th><?= date('d/m', strtotime($date)) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($datas as $agence): ?>
                        <tr>
                            <th><?= $agence['id'] ?></th>
                            <th><?= $agence['libelle'] ?></th>
                            
                            <?php foreach($dates as $date): ?>
                               
                                <?php 
                                    $color = $defaultEtat->coulagence_hexa; 
                                    $etat = '';
                                    $day = '';
                                ?>
                            

                                <?php foreach($agence['datas'] as $data): ?>
                                    <?php if($data['date'] == $date): ?>
                                        <?php 
                                            $color = $data['couleur']; 
                                            $etat = $data['etat'];
                                        ?>

                                    <?php endif; ?>

                                <?php endforeach; ?>

                                <?php 
                                    $jourSemaine = date('w', strtotime($date)); //var_dump($jourSemaine, $date);

                                ?>

                                <td class="calendar_day2  <?= ($jourSemaine == '0') ? ' calendar_off' : '' ?>" data-agence="<?= $agence['id'] ?>" data-day="<?= $date ?>" data-etat="<?= $etat ?>" style="background-color: <?= $color ?>">&nbsp;&nbsp;&nbsp;</td>
                            <?php endforeach; ?>
                                
                        </tr>
                                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    setTimeout(function () { location.reload(true); }, 60000);  

    
</script>