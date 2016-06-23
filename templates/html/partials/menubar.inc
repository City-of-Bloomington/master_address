<div class="menuBar">
	<ul><li><a href="<?= BASE_URL; ?>">Home</a></li>
        <?php
            $resources = [
                'Plat'         => 'Search Plats',
                'Contact'      => 'Contacts',
                'Report'       => 'Activity Report',
                'Intersection' => 'Intersections'
            ];
            foreach ($resources as $resource => $label) {
                if (userIsAllowed($resource)) {
                    $controller = strtolower($resource).'s';
                    $url        = BASE_URL.'/'.$controller;
                    echo "<li><a href=\"$url\">$label</a></li>";
                }
            }
        ?>
	</ul>
</div>
