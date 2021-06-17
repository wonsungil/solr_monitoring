<?php

namespace App\Http\Controllers\Models;

use Illuminate\Contracts\Support\Jsonable;

class SolrReplica implements Jsonable {

    public $shard_name ='';
    public $range = '';
    public $shard_state = '';
    public $core = '';
    public $core_node = '';
    public $base_url = '';
    public $node_name = '';
    public $state = '';
    public $ip = '';
    public $leader = false;

    public function __construct($shard_name, $range, $shard_state, $core, $core_node, $base_url, $node_name, $state, $leader)
    {
        $this->shard_name = $shard_name;
        $this->range = $range;
        $this->shard_state = $shard_state;
        $this->core = $core;
        $this->core_node = $core_node;
        $this->base_url = $base_url;
        $this->node_name = $node_name;
        $this->state = $state;
        $this->ip = explode(':', $this->node_name)[0];
        $this->leader = $leader;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0) {
        return json_encode(get_object_vars($this));
    }
}