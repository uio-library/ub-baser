<?php

namespace Database\Factories;

use App\Bases\Bibsys\BibsysObjekt;
use Illuminate\Database\Eloquent\Factories\Factory;

class BibsysObjektFactory extends Factory
{
    protected $marcRec = '*000 001114565
*008  $ap $bv $cnob $hno
*015  $anf0010333
*020  $a82-570-4473-3 $bh.
*082k $a352.048
*100  $aDisch, Per Gunnar $d1963-
*245  $aRegionnivået i Norge, Danmark og Sverige på 1990-tallet $cPer Gunnar Disch og Torunn Omland ; Per Kristen Mydske (red.)
*260  $aOslo $bInstitutt for statsvitenskap, Universitetet i Oslo ; i samarbeid med Unipub $cc1999
*300  $aIV, 70 s.
*491  $aForskningsrapport / Institutt for statsvitenskap $n920589170 $q1999:1 $v1/1999
*500  $aFørst utgitt som nr 3 med feilaktig ansvarsangivelse
*500  $a\"...skrevet som en del av prosjektet \"Regionkommunen i det politiske
*691**$afylkespolitikk norge sverige danmark
*700  $aGranlund, Torunn Omland $d1970-
*700  $aMydske, Per Kristen $d1940-
';

    protected $marcRecText = '001114565 p v nob no nf0010333 82-570-4473-3 h. 352.048 Disch, Per Gunnar 1963- Regionnivået i Norge, Danmark og Sverige på 1990-tallet Per Gunnar Disch og Torunn Omland ; Per Kristen Mydske (red.) Oslo Institutt for statsvitenskap, Universitetet i Oslo ; i samarbeid med Unipub c1999 IV, 70 s. Forskningsrapport / Institutt for statsvitenskap 920589170 1999:1 1/1999 Først utgitt som nr 3 med feilaktig ansvarsangivelse "...skrevet som en del av prosjektet "Regionkommunen i det politiske fylkespolitikk norge sverige danmark Granlund, Torunn Omland 1970- Mydske, Per Kristen 1940-';

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BibsysObjekt::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'objektid' => $this->faker->numerify('##########'),
            'title_statement' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'pub_date' => $this->faker->year,
            'marc_record' => $this->marcRec,
            'marc_record_text' => $this->marcRecText,
        ];
    }
}
