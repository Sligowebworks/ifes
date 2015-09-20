<?php

class EView extends ElectionView{
    var $id         = '';
    var $table      = '';

    function view() {
        $this->get_country();
              
        echo $this->whats_at_stake();
        $this->get_government();
        
        if ($this->election_type==1) {
            // presidential
            $this->get_electoral();
            if($this->multiple_results && $this->round_num<10 && $this->round_num>0) {
                foreach($this->all_stages as $id) {
                    echo $this->get_presidential_candidates($id);
                    if ($this->last_election_notes!='')
                        echo $this->get_presidential_last_election();
                }        
            } else {
                echo $this->get_presidential_candidates($this->id);
                if ($this->last_election_notes!='')
                    echo $this->get_presidential_last_election();
            }

        } else if ($this->election_type==2 || $this->election_type==3) {
            // parlimentary
            $this->get_electoral();  
            if($this->multiple_results && $this->round_num<10 && $this->round_num>0) {
                foreach($this->all_stages as $id) {
                    echo $this->get_parl_candidates($id);
                    if ($this->last_election_notes!='')
                        echo $this->get_parl_last_election();
                }        
            } else {
                echo $this->get_parl_candidates($this->id);
                if ($this->last_election_notes!='')
                    echo $this->get_parl_last_election();
            }
        } else if ($this->election_type==4) {  
            if($this->multiple_results && $this->round_num<10 && $this->round_num>0) {
                foreach($this->all_stages as $id) {
                    echo $this->get_ref_candidates($id);
                    if ($this->last_election_notes!='')
                        echo $this->get_ref_last_election();
                }        
            } else {
                echo $this->get_ref_candidates($this->id);
                if ($this->last_election_notes!='')
                    echo $this->get_ref_last_election();
            }
        }
        $this->get_population();
    }
}
?>
