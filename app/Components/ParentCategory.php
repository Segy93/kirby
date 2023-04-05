<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
 *
 */
class ParentCategory extends BaseComponent {
    protected $composite = true;
    protected $css       = ['ParentCategory/css/ParentCategory.css'];
    protected $js        = ['ParentCategory/js/ParentCategory.js'];
    private $name      = "";

    public function __construct($name) {
        $this->name = $name;
    }

    public function renderHTML() {
        $data = [
            "mobilni-računari" => [
                "Mobilni računari" => [
                    [
                        "name" => "Laptop",
                        "img"  => "default_pictures/CategoryPictures/Laptopovi.png"
                    ],
                    [
                        "name" => "Tableti",
                        "img"  => "default_pictures/CategoryPictures/Tableti.png"
                    ],
                ],
                "Dodatna oprema" => [
                    [
                        "name" => "Baterije",
                        "img"  => "default_pictures/CategoryPictures/Baterije.png"
                    ],
                    [
                        "name" => "Doking station",
                        "img"  => "default_pictures/CategoryPictures/doking station.png"
                    ],
                    [
                        "name" => "Punjači",
                        "img"  => "default_pictures/CategoryPictures/punjaci.png"
                    ],
                    [
                        "name" => "Hladnjaci za laptopove",
                        "img"  => "default_pictures/CategoryPictures/Notepal.png"
                    ],
                    [
                        "name" => "Torbe i futrole",
                        "img"  => "default_pictures/CategoryPictures/Torbe i futrole.png"
                    ],
                    [
                        "name" => "Zvučnici za laptopove",
                        "img"  => "default_pictures/CategoryPictures/zvucnici za laptop.png"
                    ],
                ]

            ],
            "računari" => [
                "Računari" => [
                    [
                        "name" => "Elite računari",
                        "img"  => "default_pictures/CategoryPictures/Elite-PC.png"
                    ],
                    [
                        "name" => "Standard računari",
                        "img"  => "default_pictures/CategoryPictures/PC-desktop.png"
                    ],
                    [
                        "name" => "Brand računari",
                        "img"  => "default_pictures/CategoryPictures/Brand-PC.png"
                    ],
                    [
                        "name" => "Bundle",
                        "img"  => "default_pictures/CategoryPictures/Bundle.png"
                    ],
                    [
                        "name" => "All in one računari",
                        "img"  => "default_pictures/CategoryPictures/All-in-one-pc.png"
                    ],
                    [
                        "name" => "Serveri",
                        "img"  => "default_pictures/CategoryPictures/Srveri.png"
                    ]
                ],

                "Komponente" => [
                    [
                        "name" => "Procesori",
                        "img"  => "default_pictures/CategoryPictures/Procesori.png"
                    ],
                    [
                        "name" => "Matične ploče",
                        "img"  => "default_pictures/CategoryPictures/Maticen ploce.png"
                    ],
                    [
                        "name" => "Grafičke kartice",
                        "img"  => "default_pictures/CategoryPictures/Graficke karte.png"
                    ],
                    [
                        "name" => "Memorije",
                        "img"  => "default_pictures/CategoryPictures/Memorije.png"
                    ],
                    [
                        "name" => "Hard diskovi",
                        "img"  => "default_pictures/CategoryPictures/Hard Diskovi.png"
                    ],
                    [
                        "name" => "SSD diskovi",
                        "img"  => "default_pictures/CategoryPictures/SSD Diskovi.png"
                    ],
                    [
                        "name" => "Napajanja",
                        "img"  => "default_pictures/CategoryPictures/Napajanja.png"
                    ],
                    [
                        "name" => "Kućišta",
                        "img"  => "default_pictures/CategoryPictures/Kucista.png"
                    ],
                    [
                        "name" => "Optički uređaji",
                        "img"  => "default_pictures/CategoryPictures/Opticki uredjaji.png"
                    ],
                    [
                        "name" => "Rashladni uređaji",
                        "img"  => "default_pictures/CategoryPictures/Rashladni-uredjaji.png"
                    ],
                ],
            ],

            "periferije-i-oprema" => [

            ],
            "potrošačka-elektronika" => [

            ]
        ];

        $args = [
            'data' => $data[$this->name],
        ];
        return view('ParentCategory/templates/ParentCategory', $args);
    }
}
