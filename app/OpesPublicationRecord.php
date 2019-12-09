<?php

namespace App;

class OpesPublicationRecord extends Record
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opes_pub';


    public function papyrusid()
    {

            $repr = $this->papyrusid;
            return $repr;
    }

    public function Ser_Vol()
    {

        $repr = $this->Ser_Vol;
            return $repr;
    }

    public function Editor()
    {

        $repr = $this->id2;
            return $repr;
    }

    public function Year()
    {

        $repr = $this->Year;
            return $repr;
    }


    public function Pg_No()
    {

        $repr = $this->Pg_No;
            return $repr;
    }

    public function Photo()
    {

        $repr = $this->Photo;
            return $repr;
    }


    public function SB()
    {

        $repr = $this->SB;
            return $repr;
    }

    public function Corrections()
    {

        $repr = $this->Corrections;
            return $repr;
    }


    public function Preferred_Citation()
    {

        $repr = $this->Preferred_Citation;
            return $repr;
    }

    public function DDBDP_PMichCitation()
    {

        $repr = $this->DDBDP_PMichCitation;
            return $repr;
    }


    public function DDBDP_OMichCitation()
    {

        $repr = $this->DDBDP_OMichCitation;
            return $repr;
    }


    public function Perseus_URL()
    {

        $repr = $this->Perseus_URL;
            return $repr;
    }

    public function DDBDP_P_REP()
    {

        $repr = $this->DDBDP_P_REP;
            return $repr;
    }

    public function DDBDP_O_REP()
    {

        $repr = $this->DDBDP_O_REP;
            return $repr;
    }
}
