<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->title
 *---------------------------------------------------------
 * Information to populate select dropdown options
 *---------------------------------------------------------
 * @param array $this->categories
 * @param array $this->entities
 * @param array $this->statuses
 * @param array $this->types
 * @param array $this->vicinities
 *
 * All Request object properties are available as template vars and are already escaped
 * @see Domain\Places\Actions\Update\Request
 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->title; ?></h1></header>
    <form method="post">
        <fieldset>
            <input name="id" type="hidden" value="<?= $this->id; ?>" />
            <?php
                $h = $this->template->getHelper('field');
                echo $h->field([
                    'name'     => 'name',
                    'id'       => 'name',
                    'label'    => $this->_('name'),
                    'value'    => $this->name,
                    'required' => true
                ]);

                echo $h->field([
                    'name'     => 'short_name',
                    'id'       => 'short_name',
                    'label'    => $this->_('short_name'),
                    'value'    => $this->short_name
                ]);

                $options = [['value'=>'']];
                foreach ($this->statuses as $s) { $options[] = ['value'=>$s]; }
                echo $h->field([
                    'name'     => 'status',
                    'id'       => 'status',
                    'label'    => $this->_('status'),
                    'type'     => 'select',
                    'options'  => $options,
                    'value'    => $this->status
                ]);


                $options = [];
                foreach ($this->vicinities as $s) { $options[] = ['value'=>$s]; }
                echo $h->field([
                    'name'     => 'vicinity',
                    'id'       => 'vicinity',
                    'label'    => $this->_('vicinity'),
                    'type'     => 'select',
                    'options'  => $options,
                    'value'    => $this->vicinity
                ]);

                echo $h->field([
                    'name'     => 'location_id',
                    'id'       => 'location_id',
                    'label'    => $this->_('location_id'),
                    'type'     => 'number',
                    'value'    => $this->location_id
                ]);

                echo $h->field([
                    'name'     => 'location_description',
                    'id'       => 'location_description',
                    'label'    => $this->_('location_description'),
                    'value'    => $this->location_description
                ]);

                echo $h->field([
                    'name'     => 'x',
                    'id'       => 'x',
                    'label'    => $this->_('x'),
                    'type'     => 'number',
                    'value'    => $this->x
                ]);

                echo $h->field([
                    'name'     => 'y',
                    'id'       => 'y',
                    'label'    => $this->_('y'),
                    'type'     => 'number',
                    'value'    => $this->y
                ]);

                echo $h->field([
                    'name'     => 'latitude',
                    'id'       => 'latitude',
                    'label'    => $this->_('latitude'),
                    'type'     => 'number',
                    'value'    => $this->latitude,
                    'attr'     => ['step'=>'0.00000001']
                ]);

                echo $h->field([
                    'name'     => 'longitude',
                    'id'       => 'longitude',
                    'label'    => $this->_('longitude'),
                    'type'     => 'number',
                    'value'    => $this->longitude,
                    'attr'     => ['step'=>'0.00000001']
                ]);

                $options = [['value'=>'']];
                foreach ($this->entities as $s) { $options[] = ['value'=>$s['entity_id'], 'label'=>$s['entity_name']]; }
                echo $h->field([
                    'name'     => 'entity_id',
                    'id'       => 'entity_id',
                    'label'    => $this->_('entity'),
                    'type'     => 'select',
                    'options'  => $options,
                    'value'    => $this->entity_id
                ]);

                $options = [['value'=>'']];
                foreach ($this->categories as $s) { $options[] = ['value'=>$s['category_id'], 'label'=>$s['category_name']]; }
                echo $h->field([
                    'name'     => 'category_id',
                    'id'       => 'category_id',
                    'label'    => $this->_('category'),
                    'type'     => 'select',
                    'options'  => $options,
                    'value'    => $this->category_id
                ]);

                $options = [];
                foreach ($this->types as $s) { $options[] = ['value'=>$s]; }
                echo $h->field([
                    'name'     => 'type',
                    'id'       => 'type',
                    'label'    => $this->_('type'),
                    'type'     => 'select',
                    'options'  => $options,
                    'value'    => $this->type
                ]);

                echo $h->field([
                    'name'     => 'map_label1',
                    'id'       => 'map_label1',
                    'label'    => $this->_('map_label1'),
                    'value'    => $this->map_label1
                ]);
                echo $h->field([
                    'name'     => 'map_label2',
                    'id'       => 'map_label2',
                    'label'    => $this->_('map_label2'),
                    'value'    => $this->map_label2
                ]);

                $options = [
                    ['value'=>1, 'label'=>$this->_('yes')],
                    ['value'=>0, 'label'=>$this->_('no' )]
                ];
                echo $h->field([
                    'name'     => 'landmark_flag',
                    'id'       => 'landmark_flag',
                    'label'    => $this->_('landmark_flag'),
                    'type'     => 'select',
                    'options'  => $options,
                    'value'    => $this->landmark_flag
                ]);
                echo $h->field([
                    'name'     => 'publish_flag',
                    'id'       => 'publish_flag',
                    'label'    => $this->_('publish_flag'),
                    'type'     => 'select',
                    'options'  => $options,
                    'value'    => $this->publish_flag
                ]);
                echo $h->field([
                    'name'     => 'subplace_flag',
                    'id'       => 'subplace_flag',
                    'label'    => $this->_('subplace_flag'),
                    'type'     => 'select',
                    'options'  => $options,
                    'value'    => $this->subplace_flag
                ]);

                echo $h->field([
                    'name'     => 'comments',
                    'id'       => 'comments',
                    'label'    => $this->_('comments'),
                    'type'     => 'textarea',
                    'value'    => $this->comments
                ]);


                $h = $this->template->getHelper('saveAndCancelButtons');
				echo $h->saveAndCancelButtons(parent::generateUri('places.view', ['id'=>$this->id]));
            ?>
        </fieldset>
    </form>
</section>
