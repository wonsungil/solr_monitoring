<?php

namespace App\Services;

use GuzzleHttp\Client;

class ShardService {
    
    private $zookeeperService;
    private $solrService;
    
    public function __construct(SolrService $solrService, ZookeeperService $zookeeperService) {
        $this->zookeeperService = $zookeeperService;
        $this->solrService = $solrService;
    }
    
    /**
     * SolrCloud Shard List 조회
     * @return array
     */
    public function getShards() {
        
        $replicas = $this->solrService->getReplicas();
        $nodeIps = $this->solrService->getNodeIp();
        $disk_usages = $this->zookeeperService->getDiskUsage($nodeIps);
        
        $result = [];
        
        foreach ($replicas as $key => $value) {
            $info = explode("|", $key);
            $shard_name = $info[0];
            $ip = $info[1];
            $value->disk_usage = array_key_exists($shard_name, $disk_usages[$ip]) ? $disk_usages[$ip] : '';
            $result[] = $value;
        }
        
        return $result;
    }
    
    /**
     * 리플리카가 없는 샤드 조회
     * @return array
     */
    public function getSingleShard() {
        
        $shards = $this->solrService->getShards();
        
        $result = [];
        foreach ($shards as $key => $shard) {
            if (count(collect($shard->replicas)) < 2) {
                $result[$key] = $shard;
            }
        }
        return $result;
    }
}