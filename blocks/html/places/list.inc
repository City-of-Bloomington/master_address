<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->places
 */
declare (strict_types=1);
use Application\Block;
use Application\Paginator;
?>
<header><h2><?= $this->_(['place', 'places', 100]); ?></h2></header>
<?php
    if (!$this->disableButtons && parent::isAllowed('places', 'add')) {
        $block = new Block('toolbox.inc', ['actions' => [
            'add' => [
                'uri'   => parent::generateUri("places.add"),
                'class' => 'add',
                'help'  => $this->_("places.add", 'messages')
            ]
        ]]);
        echo $block->render('html', $this->template);
    }
?>

<table>
    <thead>
        <tr>
        <?php
            $fields = [
                'id', 'name', 'status', 'category_name', 'type', 'subplace_flag', 'entity_name'
            ];
            foreach ($fields as $f) { echo "<th>{$this->_($f)}</th>"; }
        ?>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach ($this->places as $p) {
            $uri = parent::generateUri('places.view', ['id'=>$p->id]);
            echo '<tr>';
            foreach ($fields as $f) {
                echo "<td><a href=\"$uri\">".parent::escape($p->$f).'</a></td>';
            }
            echo '</tr>';
        }
    ?>
    </tbody>
</table>
<?php
    if ($this->total > $this->itemsPerPage) {
        $this->paginator = new Paginator(
            $this->total,
            $this->itemsPerPage,
            $this->currentPage
        );
        $this->_include('pageNavigation.inc');
    }
?>
