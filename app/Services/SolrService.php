<?php

namespace App\Services;

use App\Http\Controllers\Models\SolrShard;
use GuzzleHttp\Client;
use Log;

class SolrService {
    
    private $zookeeperService;
    
    private $CLUSTER_URL;
    private $OVERSEER_STATUS_URL;
    private $REQUEST_STATUS_URL;
    private $COLLECTION_NAME;
    
    private $client;
    
    public function __construct(ZookeeperService $zookeeperService) {
        
        $this->zookeeperService = $zookeeperService;
        
        $this->CLUSTER_URL = 'http://${SOLR_HOST}:${SOLR_PORT}/solr/admin/collections?action=CLUSTERSTATUS&wt=json';
        $this->OVERSEER_STATUS_URL = 'http://${SOLR_HOST}:${SOLR_PORT}/solr/admin/collections?action=OVERSEERSTATUS&wt=json';
        $this->REQUEST_STATUS_URL = 'http://${SOLR_HOST}:${SOLR_PORT}/solr/admin/collections?action=REQUESTSTATUS&wt=json';
        $this->COLLECTION_NAME = ''; // 콜렉션 명 
        
        $this->client = $this->getClient(); 
    }
    
    // Client 생성
    private function getClient() {
        if ($this->client == null) {
            return new Client();
        }
        return $this->client;
    }
    
    /**
     * CLUSTER STATUS 조회
     * @return mixed|null
     */
    public function getCluster() {
        $response = $this->getClient()->request('GET', $this->CLUSTER_URL);
        
        if ($response->getStatusCode() != 200) {
            Log::error("SOLR SERVER ERROR {$response->getStatusCode()}");
            return null;
        }
        
        return json_decode($response->getBody()->getContents());
    }
    
    /**
     * 샤드정보 조회
     * @param $cluster
     * @return null
     */
    public function getShards() {
        $cluster = $this->getCluster();
        return $cluster->cluster->collections->{$this->COLLECTION_NAME}->shards;
    }
    
    /**
     * Replication 조회
     * @param $shards
     * @return array|null
     */
    public function getReplicas() {
        $shards = $this->getShards();
        return collect($shards)->mapWithKeys(function($item, $key) {
            return (new SolrShard($key, $item->range, $item->state, $item->replicas))->reverseReplicas();
        })->toArray();
    }
    
    /**
     * Node IP 조회
     * @param $replicas
     * @return mixed
     */
    public function getNodeIp() {
        $replicas = $this->getReplicas();
        return collect($replicas)->pluck("node_name")->map(function($item) {
            if (!explode(":", $item)) {
                return "";
            }
            return explode(":", $item)[0]; 
        })->toArray();
    }
    
    /**
     * OVERSEER 조회
     * @return mixed|null
     */
    public function getOverseer() {
        $response = $this->getClient()->request('GET', $this->OVERSEER_STATUS_URL);
    
        if ($response->getStatusCode() != 200) {
            Log::error("SOLR SERVER ERROR {$response->getStatusCode()}");
            return null;
        }
    
        return json_decode($response->getBody()->getContents());
    }
    
    /**
     * OPERATION 조회
     * @param $overseer
     * @return array|null
     */
    public function getCollectionOperations() {
        $overseer = $this->getOverseer();
        
        $result = [];
        if($overseer) {
            $ops = $overseer->collection_operations;
            $chunks = collect($ops)->chunk(2);
            $result = $chunks->mapSpread(function($key, $value) {
                return [$key => $value];
            })->collapse();
        }
        return $result;
    }
    
    /** ASYNC JON STATUS CHECK
     * @param $asyncId
     * @return mixed|null
     */
    public function getRequestStatus($asyncId) {
        $response = $this->getClient()->request('GET', $this->REQUEST_STATUS_URL. $asyncId);
    
        if ($response->getStatusCode() != 200) {
            Log::error("SOLR SERVER ERROR {$response->getStatusCode()}");
            return null;
        }
    
        return json_decode($response->getBody()->getContents());
    }
    
    
    /**
     * solr 샤드 그래프용 데이터 조회
     * @param $state
     * @return array|null
     */
    public function getShardGraph($state) {
        if ($state == null) {
            Log::error("INVALID SOLR STATE");
            return null;
        }
    
        $shardGraph = [];
        $shardGraph['name'] = 'root';
    
        if ($state) {
            $shards = $state->tibuzz->shards;
        
            foreach($shards as $key => $shard) {
                $shd = [];
                $shd['name'] = $key;
                $shd['children'] = [];
            
                if(array_key_exists('replicas', $shard)) {
                    foreach ($shard->replicas as $replica) {
                        $rep = [];
                        $rep['name'] = $replica->node_name;
                        $rep['leader'] = array_key_exists('leader', $replica) ? $replica->leader:"false";
                        $shd['children'][] = $rep;
                    }
                }
                $shardGraph['children'][] = $shd;
            }
        }
    
        return $shardGraph;
    }
    
    /**
     * 에러 명령어 리스트 조회
     * @return array
     */
    public function getCollectionErrorOperations() {
        $operations = $this->getCollectionOperations();
        
        $erros = [];
        foreach ($operations as $key => $value) {
            $erros[$key] = property_exists($value, 'recent_failures')?$value->recent_failures:[]; 
        }
        return $erros;
    }

    /**
     * 디스크 사용량 및 샤드 카운팅
     * @return array
     */
    public function getShardCountDiskUsage() {
        $nodeIp = $this->getNodeIp();
        $disk_usages = $this->zookeeperService->getDiskUsage($nodeIp);
    
        $collect = collect($disk_usages)
            ->flatten()
            ->groupBy(function($item, $key) {
                return substr($item, 0, 1).'0GB';
            })
            ->map(function($value) {
                return count($value);
            })
            ->sortKeysDesc();
    
        $result = [];
        foreach ($collect as $key => $value) {
            $result[] = ['label'=> '>= '.$key, 'leader_cnt'=>$value/2, 'replica_cnt'=>$value];
        }
        return $result;
    }
    
    /**
     * 스플릿 전후 계산
     * @return array
     */
    public function getReplicaSplitShard() {
        $nodeIp = $this->getNodeIp();
        $disk_usages = $this->zookeeperService->getDiskUsage($nodeIp);
    
        $collect = collect($disk_usages)
            ->flatten()
            ->groupBy(function($item, $key) {
                return substr($item, 0, 1).'0GB';
            })
            ->map(function($value) {
                return count($value);
            })
            ->sortKeysDesc();
    
        $result = [];
        $current_total = $collect->sum();
        foreach ($collect as $key => $value) {
            $result[] = [
                'label'=> '>= '.$key,
                'beforeSplit' => $value,
                'afterSplit' => $value*2,
                'afterSplitTotalReplica' => $current_total + $value,
                'afterSplitTotalLeader' => ($current_total + $value)/2,
            ];
        }
    
        $result[] = ['label' => 'Total Replica', 'beforeSplit' => $current_total, 'afterSplit' => $current_total *2];
        return $result;
    }
    
    /**
     * 노드 당 샤드 계산
     * @param int $nodeCnt
     * @return array
     */
    public function getAvgShardPerNode($nodeCnt=67) {      
        $nodeIp = $this->getNodeIp();
        $disk_usages = $this->zookeeperService->getDiskUsage($nodeIp);
    
        $collect = collect($disk_usages)
            ->flatten()
            ->groupBy(function($item, $key) {
                return substr($item, 0, 1).'0GB';
            })
            ->map(function($value) {
                return count($value);
            })
            ->sortKeysDesc();
    
        $result = [];
        $current_total = $collect->sum();
        $result[] = ['label' => 'current', 'totalReplica' => $current_total, 'avgReplicaPerNode' => round($current_total/$nodeCnt,1), 'avgLeaderPerNode' => round(($current_total/$nodeCnt)/2,1)];
        foreach ($collect as $key => $value) {
            $current_total = $current_total + $value;
            $result[] = [
                'label'=> 'Split All Over '.$key,
                'totalReplica' => $current_total,
                'avgReplicaPerNode' => round($current_total/$nodeCnt,1),
                'avgLeaderPerNode' => round(($current_total/$nodeCnt)/2,1),
            ];
        }
        return $result;
    }
    
    /**
     * 샤드당 리플리카수 계산
     */
    public function getShardReplCnt() {
        $replicas = $this->getReplicas();
        $ips = $this->getNodeIp();
        $disk_usages = $this->zookeeperService->getDiskUsage($ips);
    
        $group = [];
        foreach ($replicas as $key => $value) {
            $key_split = explode('|', $key);
            $shard_name = $key_split[0];
            $ip = $key_split[1];
            $value->disk_usage = array_key_exists($shard_name, $disk_usages[$ip]) ? $disk_usages[$ip][$shard_name] : '';
            $group[] = $value;
        }
    
        $rp = collect($group)->groupBy("shard_name");
        $result = [];
        foreach ($rp as $key=>$value) {
            $result[] =  ['shard_name' => $key, 'cnt' => count($value)];
        }
        return $result;
    }
    
}