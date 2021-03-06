<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->roles
 * @param array $this->authentication_methods
 */
$fields = ['firstname', 'lastname', 'username', 'email', 'role', 'authentication_method'];
foreach ($fields as $f) {
	$$f = isset($_GET[$f]) ? parent::escape($_GET[$f]) : '';
}
?>
<section>
    <header>
        <h1><?= $this->_('users_search'); ?></h1>
    </header>
    <form method="get" action="<?= parent::generateUri('users.index'); ?>">
        <fieldset>
            <?php
                $h = $this->template->getHelper('field');

                foreach (['firstname', 'lastname', 'username', 'email'] as $f) {
                    echo $h->field([
                        'label' => $this->_($f),
                        'name'  => $f,
                        'id'    => $f,
                        'value' => $$f
                    ]);
                }

				$options = [['value'=>'']];
				foreach ($this->roles as $r) { $options[] = ['value'=>$r]; }
				echo $h->field([
                    'name'     => 'role',
                    'id'       => 'role',
                    'label'    => $this->_('role'),
                    'value'    => $role,
                    'type'     => 'select',
                    'options'  => $options
				]);


                $options = [['value'=>'']];
                foreach ($this->authentication_methods as $m) { $options[] = ['value'=>$m]; }
                echo $h->field([
                    'name'     => 'authentication_method',
                    'id'       => 'authentication_method',
                    'label'    => $this->_('authentication_method'),
                    'value'    => $authentication_method,
                    'type'     => 'select',
                    'options'  => $options
                ]);

                // Preserve any extra parameters passed in
                foreach ($_REQUEST as $key=>$value) {
                    if (!in_array($key, $fields)) {
                        $key   = self::escape($key);
                        $value = self::escape($value);
                        echo "<input type=\"hidden\" name=\"$key\" value=\"$value\" />\n";
                    }
                }
            ?>
            <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
    <?php
        if ($this->users) { $this->_include('users/list.inc'); }
    ?>
</section>
