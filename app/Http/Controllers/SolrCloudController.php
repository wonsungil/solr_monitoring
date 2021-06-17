<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Http\Controllers\Models\SolrShard;
use Illuminate\Http\Request;
use Kyoz\ZookeeperClient;

class SolrCloudController {

    public function shards() {
        return view('shards');
    }
    
    public function nodeInfo() {
        return view('leaders');
    }
    
    public function operations() {
        return view('operations');
    }
    
    public function runningMap() {
        return view('runningMap');
    }
    
    public function state() {
        return view('state');
    }
    
    public function shardGraph() {
        return view('shardGraph');
    }
    
    public function diskUsageGroupBy() {
        return view('diskUsageGroupBy');
    }
    
    public function nodes() {
        return view('nodes');
    }
    
    public function shardReplicaCnt() {
        return view('shardReplicaCnt');
    }
}
