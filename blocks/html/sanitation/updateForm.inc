<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param int    $this->location_id
 * @param string $this->trash_day
 * @param string $this->recycle_week
 *
 * @param array  $this->trashDays     Array of days to choose from
 * @param array  $this->recycleWeeks  Array of weeks to choose from
 * @param string $this->return_url
 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->_('sanitation_edit'); ?></h1></header>
    <form method="post">
        <fieldset>
            <input name="location_id" type="hidden" value="<?= $this->location_id; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                $options = [['value'=>'']];
                foreach ($this->trashDays as $d) { $options[] = ['value'=>$d, 'label'=>$this->_($d)]; }
                echo $h->field([
                    'name'     => 'trash_day',
                    'id'       => 'trash_day',
                    'label'    => $this->_('trash_day'),
                    'type'     => 'select',
                    'value'    => $this->trash_day,
                    'options'  => $options
                ]);

                $options = [['value'=>'']];
                foreach ($this->recycleWeeks as $w) { $options[] = ['value'=>$w]; }
                echo $h->field([
                    'name'     => 'recycle_week',
                    'id'       => 'recycle_week',
                    'label'    => $this->_('recycle_week'),
                    'type'     => 'select',
                    'value'    => $this->recycle_week,
                    'options'  => $options
                ]);

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons($this->return_url);
            ?>
        </fieldset>
    </form>
</section>
