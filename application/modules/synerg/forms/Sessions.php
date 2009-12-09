<?

class Synerg_Form_Sessions
extends Zupal_Fastform_Domainform
{

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ _init @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
/**
 *
 */
    public function _init () {
    }

    protected function _domain_class() {
        return 'Synerg_Model_Gamesessions';
    }

    protected function _ini_path() {
        return preg_replace('~php$~', 'ini', __FILE__);
    }

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ save @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
    /**
     *
     */
    public function save () {
        $sgt = Synerg_Model_Gametypes::synergy_gametype();
        $sgt_id = $sgt->identity();

        return parent::save();
    }

}