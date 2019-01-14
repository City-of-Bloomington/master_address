<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\Entities;

class Intersection
{
    public $id;
    public $type_id;
    public $name;
    public $construction_status;
    public $gis_tag;
    public $jurisdiction_id;
    public $custodian_id;
    public $traffic_control;
    public $state_plane_x;
    public $state_plane_y;
    public $latitude;
    public $longitude;
    public $notes;

    public function __construct(?array $data=null)
    {
        if ($data) {
            if (!empty($data['id'                 ])) { $this->id                 =   (int)$data['id'                 ]; }
            if (!empty($data['type_id'            ])) { $this->type_id            =   (int)$data['type_id'            ]; }
            if (!empty($data['name'               ])) { $this->name               =        $data['name'               ]; }
            if (!empty($data['construction_status'])) { $this->construction_status=        $data['construction_status']; }
            if (!empty($data['gis_tag'            ])) { $this->gis_tag            =        $data['gis_tag'            ]; }
            if (!empty($data['jurisdiction_id'    ])) { $this->jurisdiction_id    =   (int)$data['jurisdiction_id'    ]; }
            if (!empty($data['custodian_id'       ])) { $this->custodian_id       =        $data['custodian_id'       ]; }
            if (!empty($data['traffic_control'    ])) { $this->traffic_control    =        $data['traffic_control'    ]; }
            if (!empty($data['state_plane_x'      ])) { $this->state_plane_x      =   (int)$data['state_plane_x'      ]; }
            if (!empty($data['state_plane_y'      ])) { $this->state_plane_y      =   (int)$data['state_plane_y'      ]; }
            if (!empty($data['latitude'           ])) { $this->latitude           = (float)$data['latitude'           ]; }
            if (!empty($data['longitude'          ])) { $this->longitude          = (float)$data['longitude'          ]; }
            if (!empty($data['notes'              ])) { $this->notes              =        $data['notes'              ]; }
        }
    }
}
