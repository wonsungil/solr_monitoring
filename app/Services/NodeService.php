<?php

namespace App\Services;

class NodeService
{
    
    private $zookeeperService;
    private $solrService;
    
    public function __construct(SolrService $solrService, ZookeeperService $zookeeperService)
    {
        $this->zookeeperService = $zookeeperService;
        $this->solrService = $solrService;
    }
    
    /**
     * 노드 리스트 서버 정보 조회
     * @return array
     */
    public function getNodeExtra() {
    
        $replicas = $this->solrService->getReplicas();
        $nodeIps = $this->solrService->getNodeIp();
        $disk_usages = $this->zookeeperService->getDiskUsage($nodeIps);
        $zk_node = $this->zookeeperService->loadNode('/disk_usage');
    
        $result = [];
        foreach ($replicas as $key => $value) {
            $key_split = explode('|', $key);
            $shard_name = $key_split[0];
            $ip = $key_split[1];
            $value->disk_usage = array_key_exists($shard_name, $disk_usages[$ip]) ? $disk_usages[$ip][$shard_name] : '';
            $result[] = $value;
        }
    
        $groupShards = collect($result)->groupBy('ip');
        $nodes = $groupShards->keys();
        $result = [];
        
        foreach ($nodes as $ip) {
        
            $server_disk_usage = json_decode($zk_node[$ip], true);
            $leader = $groupShards[$ip]->pluck('leader')->countBy();
            $tmp['ip'] = $ip;
            $tmp['leader_count'] = isset($leader[1]) ? $leader[1]: 0;
            $tmp['total'] = count($disk_usages[$ip]);
            $tmp['total_disk'] = round($server_disk_usage['total']/1000000000,0);
            $tmp['used'] = round($server_disk_usage['used']/1000000000,0);
            $tmp['free'] = $tmp['total_disk'] - $tmp['used'];
            $tmp['used_per'] = round($server_disk_usage['used'] / $server_disk_usage['total'] * 100, 2);
            
        
            $tmp['java_version'] = '';  // todo
            $tmp['jvm_heap'] ='';       // todo 
            $tmp['solr_version'] = '';  // todo
            $tmp['os'] = '';            // todo
            $result[] = $tmp;
        }
        
        return $result;
    }
    
    /**
     * 노드 리스트 조회
     * @return mixed
     */
    public function getNodes() {
        $replicas = $this->solrService->getReplicas();
        $ips = $this->solrService->getNodeIps($replicas);
        $disk_usages = $this->zookeeperService->getDiskUsage($ips);
        $result = [];
    
        $zk_node = $this->zookeeperService->loadNode('/disk_usage');
        foreach ($replicas as $key => $value) {
            $key_split = explode('|', $key);
            $shard_name = $key_split[0];
            $ip = $key_split[1];
            $value->disk_usage = array_key_exists($shard_name, $disk_usages[$ip]) ? $disk_usages[$ip][$shard_name] : '';
            $result[] = $value;
        }
    
        $nodes = collect($result)
            ->groupBy('ip')
            ->map(function($item) use ($zk_node) {
                $ip = $item[0]->ip;
                $server_disk_usage = json_decode($zk_node[$ip], true);
                return [
                    'ip' => $ip,
                    'shard_cnt' => count($item) . '('.collect($item)->where('leader', true)->count().')',
                    'disk_usage_per' => round($server_disk_usage['used'] / $server_disk_usage['total'] * 100, 2),
                    'shards' => $item,
                ];
            })
            ->values();
        return $nodes;
    }
}
    