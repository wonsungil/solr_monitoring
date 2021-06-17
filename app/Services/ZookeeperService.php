<?php

namespace App\Services;

use Kyoz\ZookeeperClient;
use GuzzleHttp\Client;

class ZookeeperService {

    private $SC_ZK_URL = '';
    private $ZK_CLIENT = null;
    private $ZK_NODE = null;
    
    public function __construct() {
        $this->SC_ZK_URL = '116.121.29.90:2181';
    }
    
    public function getClient() {
        if($this->ZK_CLIENT == null) {
            $this->ZK_CLIENT = new ZookeeperClient($this->SC_ZK_URL);
        }
        return $this->ZK_CLIENT;
    }
    
    public function loadNode($path) {
        if($this->ZK_NODE != null) {
            $this->ZK_NODE;
        }
        
        $zk_client = $this->ZK_CLIENT ? $this->ZK_CLIENT : $this->getClient();
        $this->ZK_NODE = $zk_client->loadNode($path);
        return $this->ZK_NODE;
    }
    
    public function getDiskUsage($ips) {
        $zk_node = $this->loadNode('/disk_usage');
        $disk_usages = [];
        
        foreach ($ips as $ip) {
            $usages = [];
            foreach (json_decode($zk_node['shard_'.$ip], true) as $key => $value) {
                $path = explode('/solr/', $key);
                $index = count($path) == 2 ? 1:2;
                $usages[$path[$index]] = $value;
            }
            $disk_usages[$ip] = $usages;
        }
        return $disk_usages;
    }
    
    public function getRunningMap() {
        return collect($this->loadNode('/overseer/collection-map-running'))
            ->keys()
            ->map(function($item) {
                return explode('-', $item)[1];
            })
            ->all();
    }
    
    public function getState() {
        $state = json_decode($this->getClient()->get('/collections/tibuzz/state.json'));
        return $state;
    }
}