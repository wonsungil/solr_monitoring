<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Models\SolrReplica;

class SolrShard {

    public $name ='';
    public $range = '';
    public $shard_state = '';
    public $replicas = [];

    public function __construct($name, $range, $state, $replicas) {
        $this->name = $name;
        $this->range = $range;
        $this->shard_state = $state;
        $this->replicas = $replicas;
    }

    public function reverseReplicas() {
        $replicas = [];
        if($this->replicas) {
            foreach ($this->replicas as $key=>$replica) {
                $leader = false;
                if(isset($replica->leader)) {
                    $leader = true;
                }

                $r = new SolrReplica($this->name, $this->range, $this->shard_state, $replica->core, $key, $replica->core, $replica->node_name, $replica->state, $leader);
                $replicas[$replica->core.'|'.$r->ip] = $r;
//                $replicas[] = $r;
            }
        }
        return $replicas;
    }

}